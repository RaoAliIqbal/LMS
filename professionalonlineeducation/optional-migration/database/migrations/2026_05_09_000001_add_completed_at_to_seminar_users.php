<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedAtToSeminarUsers extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('seminar_users') || Schema::hasColumn('seminar_users', 'completed_at')) {
            return;
        }

        Schema::table('seminar_users', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('enrolled_at');
        });
    }

    public function down()
    {
        if (! Schema::hasTable('seminar_users') || ! Schema::hasColumn('seminar_users', 'completed_at')) {
            return;
        }

        Schema::table('seminar_users', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
}
