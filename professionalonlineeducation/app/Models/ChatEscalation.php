<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ChatEscalation extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'course_name', 'issue',
        'type', 'token', 'follow_up_token', 'follow_up_sent_at', 'status',
    ];

    protected $dates = ['follow_up_sent_at'];

    /**
     * Resolve public follow-up link token (some DBs use `token`, others `follow_up_token`).
     */
    public static function findByPublicToken(string $token): ?self
    {
        if ($token === '' || ! Schema::hasTable('chat_escalations')) {
            return null;
        }

        $cols = Schema::getColumnListing('chat_escalations');
        $lower = array_map('strtolower', $cols);

        $idx = array_search('token', $lower, true);
        if ($idx !== false) {
            $row = static::query()->where($cols[$idx], $token)->first();
            if ($row) {
                return $row;
            }
        }

        $idx = array_search('follow_up_token', $lower, true);
        if ($idx !== false) {
            return static::query()->where($cols[$idx], $token)->first();
        }

        return null;
    }

    /**
     * @param  mixed  $value
     * @return mixed
     */
    public function getTokenAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return $value;
        }
        if (isset($this->attributes['follow_up_token']) && $this->attributes['follow_up_token'] !== '') {
            return $this->attributes['follow_up_token'];
        }

        return $value;
    }

    public function typeLabel(): string
    {
        switch ($this->type) {
            case 'instructor':
                return 'Instructor Support';
            case 'customer_service':
                return 'Customer Service';
            default:
                return 'IT Support';
        }
    }

    public function getCourseNameAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return $value;
        }
        if (isset($this->attributes['course']) && $this->attributes['course'] !== '') {
            return $this->attributes['course'];
        }

        return $value;
    }
}
