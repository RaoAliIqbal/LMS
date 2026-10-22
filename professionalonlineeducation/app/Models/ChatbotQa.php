<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotQa extends Model
{
    protected $table = 'chatbot_qas';

    protected $fillable = [
        'topic_key', 'label', 'tip_html', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function topicOptions(): array
    {
        return [
            'login' => 'Login / LMS access',
            'billing' => 'Billing / payment',
            'course' => 'Course / instructor',
            'certificate' => 'Certificate / CE',
            'other' => 'Something else',
        ];
    }
}
