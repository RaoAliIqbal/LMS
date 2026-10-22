<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatbotQasTable extends Migration
{
    public function up()
    {
        Schema::create('chatbot_qas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('topic_key', 40);
            $table->string('label', 255)->nullable();
            $table->text('tip_html');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['topic_key', 'is_active', 'sort_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chatbot_qas');
    }
}
