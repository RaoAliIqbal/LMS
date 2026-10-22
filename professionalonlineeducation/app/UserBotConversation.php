<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBotConversation extends Model
{
    protected $table = 'user_bot_conversations';

    protected $fillable = [
        'question',
        'answer',
        'ip',
    ];
}
