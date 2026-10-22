<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;
    protected $tokenUrl;

    public function __construct()
    {
        $this->accountId    = config('zoom.account_id');
        $this->clientId     = config('zoom.client_id');
        $this->clientSecret = config('zoom.client_secret');
        $this->baseUrl      = config('zoom.base_url');
        $this->tokenUrl     = config('zoom.token_url');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS TOKEN  (cached for 55 min — Zoom tokens expire in 60 min)
    |--------------------------------------------------------------------------
    */
    public function getAccessToken()
    {
        // Don't cache a failed token — check cache manually
        if (Cache::has('zoom_access_token')) {
            return Cache::get('zoom_access_token');
        }

        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->tokenUrl, [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->failed()) {
                Log::error('Zoom OAuth failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            $json  = $response->json();
            $token = isset($json['access_token']) ? (string) $json['access_token'] : null;

            if ($token) {
                Cache::put('zoom_access_token', $token, 3300);
            } else {
                Log::error('Zoom OAuth: no access_token in response', ['body' => $response->body()]);
            }

            return $token;

        } catch (\Exception $e) {
            Log::error('Zoom OAuth exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE MEETING
    |--------------------------------------------------------------------------
    */
    public function createMeeting(array $data): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'message' => 'Could not authenticate with Zoom. Check credentials.'];
        }

        // Build start_time in Zoom format: 2026-05-10T14:00:00
        $startTime = $data['zoom_date'] . 'T' . ($data['zoom_time'] ?? '09:00') . ':00';

        $payload = [
            'topic'       => $data['topic'],
            'type'        => 2, // 2 = Scheduled meeting
            'start_time'  => $startTime,
            'duration'    => intval($data['duration'] ?? 60),
            'timezone'    => $data['timezone'] ?? 'America/Los_Angeles',
            'agenda'      => $data['agenda'] ?? '',
            'password'    => $data['password'] ?? $this->generatePassword(),
            'settings'    => [
                'host_video'             => true,
                'participant_video'      => true,
                'join_before_host'       => false,
                'mute_upon_entry'        => true,
                'waiting_room'           => true,
                'meeting_authentication' => false,
                'auto_recording'         => 'none',
            ],
        ];

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/users/me/meetings", $payload);

        if ($response->failed()) {
            Log::error('Zoom create meeting failed', ['body' => $response->body()]);
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to create Zoom meeting.',
            ];
        }

        $meeting = $response->json();

        return [
            'success'        => true,
            'meeting_id'     => (string) $meeting['id'],
            'zoom_link'      => $meeting['join_url'],
            'start_url'      => $meeting['start_url'],
            'zoom_password'  => $meeting['password'],
            'topic'          => $meeting['topic'],
            'start_time'     => $meeting['start_time'],
            'duration'       => $meeting['duration'],
            'raw'            => $meeting,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET MEETING
    |--------------------------------------------------------------------------
    */
    public function getMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed.'];
        }

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/meetings/{$meetingId}");

        if ($response->failed()) {
            return ['success' => false, 'message' => $response->json('message') ?? 'Meeting not found.'];
        }

        $meeting = $response->json();

        return [
            'success'       => true,
            'meeting_id'    => (string) $meeting['id'],
            'zoom_link'     => $meeting['join_url'],
            'start_url'     => $meeting['start_url'],
            'zoom_password' => $meeting['password'],
            'topic'         => $meeting['topic'],
            'start_time'    => $meeting['start_time'],
            'duration'      => $meeting['duration'],
            'status'        => $meeting['status'] ?? 'waiting',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE MEETING
    |--------------------------------------------------------------------------
    */
    public function updateMeeting(string $meetingId, array $data): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed.'];
        }

        $payload = [];
        if (isset($data['topic']))      $payload['topic']      = $data['topic'];
        if (isset($data['zoom_date']) && isset($data['zoom_time'])) {
            $payload['start_time'] = $data['zoom_date'] . 'T' . $data['zoom_time'] . ':00';
        }
        if (isset($data['duration']))   $payload['duration']   = intval($data['duration']);
        if (isset($data['agenda']))     $payload['agenda']     = $data['agenda'];
        if (isset($data['password']))   $payload['password']   = $data['password'];

        $response = Http::withToken($token)
            ->patch("{$this->baseUrl}/meetings/{$meetingId}", $payload);

        if ($response->status() === 204) {
            return ['success' => true, 'message' => 'Meeting updated successfully.'];
        }

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Failed to update meeting.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE MEETING
    |--------------------------------------------------------------------------
    */
    public function deleteMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed.'];
        }

        $response = Http::withToken($token)
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");

        if ($response->status() === 204) {
            return ['success' => true, 'message' => 'Meeting deleted from Zoom.'];
        }

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Failed to delete meeting.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LIST ALL MEETINGS
    |--------------------------------------------------------------------------
    */
    public function listMeetings(): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'meetings' => []];
        }

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/users/me/meetings", ['type' => 'scheduled', 'page_size' => 100]);

        if ($response->failed()) {
            return ['success' => false, 'meetings' => []];
        }

        return [
            'success'  => true,
            'meetings' => $response->json('meetings') ?? [],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    private function generatePassword(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, 8);
    }

    public function testConnection(): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return ['success' => false, 'message' => 'Failed to get access token. Check credentials.'];
        }

        $response = Http::withToken($token)->get("{$this->baseUrl}/users/me");

        if ($response->failed()) {
            return ['success' => false, 'message' => 'Connected but user fetch failed: ' . $response->body()];
        }

        $user = $response->json();

        return [
            'success' => true,
            'message' => 'Connected successfully!',
            'email'   => $user['email'] ?? '',
            'name'    => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
            'plan'    => $user['type'] ?? '',
        ];
    }
}
