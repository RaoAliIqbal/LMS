<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseNameToChatEscalationsTable extends Migration
{
    /**
     * Older installs may have chat_escalations without course_name (required by chatbot intake).
     */
    public function up()
    {
        if (! Schema::hasTable('chat_escalations')) {
            return;
        }
        if (Schema::hasColumn('chat_escalations', 'course_name')) {
            return;
        }
        Schema::table('chat_escalations', function (Blueprint $table) {
            $table->string('course_name')->nullable()->after('email');
        });
    }

    public function down()
    {
        if (! Schema::hasTable('chat_escalations')) {
            return;
        }
        if (! Schema::hasColumn('chat_escalations', 'course_name')) {
            return;
        }
        Schema::table('chat_escalations', function (Blueprint $table) {
            $table->dropColumn('course_name');
        });
    }
}
