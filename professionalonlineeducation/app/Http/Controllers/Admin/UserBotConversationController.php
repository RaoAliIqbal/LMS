<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UserBotConversation;
use App\Support\AdminDbLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserBotConversationController extends Controller
{
    public function index(){
        try {
            if (! AdminDbLoader::hasTable('user_bot_conversations')) {
                return view('admin.user_bot_conversation.index', ['data' => collect()])
                    ->with('error', 'Table `user_bot_conversations` is missing. '.AdminDbLoader::PATCH_HINT);
            }

            $data = [
                'data' => UserBotConversation::query()
                    ->select(
                        'ip',
                        DB::raw('DATE(created_at) as created_date'),
                        DB::raw('COUNT(*) as conversation_count')
                    )
                    ->groupBy('ip', DB::raw('DATE(created_at)'))
                    ->orderByDesc(DB::raw('DATE(created_at)'))
                    ->get(),
            ];

            return view('admin.user_bot_conversation.index')->with($data);
        } catch (\Throwable $e) {
            \Log::error('admin user-bot-conversation index failed', ['message' => $e->getMessage()]);

            return view('admin.user_bot_conversation.index', ['data' => collect()])
                ->with('error', 'Could not load bot conversations. '.$e->getMessage());
        }
    }

    public function search($ip, $date){
        try {
            if (! AdminDbLoader::hasTable('user_bot_conversations')) {
                return view('admin.user_bot_conversation.search', [
                    'ip' => $ip,
                    'date' => $date,
                    'result' => collect(),
                ])->with('error', 'Table `user_bot_conversations` is missing. '.AdminDbLoader::PATCH_HINT);
            }

            $data = [
                'ip' => $ip,
                'date' => $date,
                'result' => UserBotConversation::where('ip', $ip)->whereDate('created_at', $date)->get(),
            ];

            return view('admin.user_bot_conversation.search')->with($data);
        } catch (\Throwable $e) {
            \Log::error('admin user-bot-conversation search failed', ['message' => $e->getMessage()]);

            return view('admin.user_bot_conversation.search', [
                'ip' => $ip,
                'date' => $date,
                'result' => collect(),
            ])->with('error', 'Could not load conversation. '.$e->getMessage());
        }
    }
}
