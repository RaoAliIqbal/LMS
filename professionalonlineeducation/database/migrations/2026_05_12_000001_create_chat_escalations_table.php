<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatEscalationsTable extends Migration
{
    public function up()
    {
        Schema::create('chat_escalations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('course_name')->nullable();
            $table->text('issue')->nullable();
            $table->string('type', 30)->default('it'); // it | instructor | customer_service
            $table->string('token', 80)->unique();
            $table->timestamp('follow_up_sent_at')->nullable();
            // pending | resolved | needs_help | no_help_needed
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_escalations');
    }
}
