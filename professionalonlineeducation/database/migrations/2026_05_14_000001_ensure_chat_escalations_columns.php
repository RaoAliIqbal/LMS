<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aligns older `chat_escalations` tables with what the chatbot expects (avoids 500 on insert/select).
 */
class EnsureChatEscalationsColumns extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('chat_escalations')) {
            return;
        }

        if (! Schema::hasColumn('chat_escalations', 'course_name')) {
            $afterEmail = Schema::hasColumn('chat_escalations', 'email');
            Schema::table('chat_escalations', function (Blueprint $table) use ($afterEmail) {
                if ($afterEmail) {
                    $table->string('course_name')->nullable()->after('email');
                } else {
                    $table->string('course_name')->nullable();
                }
            });
        }

        if (! Schema::hasColumn('chat_escalations', 'follow_up_sent_at')) {
            Schema::table('chat_escalations', function (Blueprint $table) {
                $table->timestamp('follow_up_sent_at')->nullable();
            });
        }

        if (! Schema::hasColumn('chat_escalations', 'status')) {
            Schema::table('chat_escalations', function (Blueprint $table) {
                $table->string('status', 30)->default('pending');
            });
        }
    }

    public function down()
    {
        // Non-destructive “ensure” migration — do not drop columns that may pre-date this patch.
    }
}
