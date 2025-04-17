<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('activity_status', ['active', 'inactive', 'resumed'])
                  ->default('active')
                  ->after('lines')
                  ->comment('Status of the activity session');
            $table->integer('inactive_gap')->default(0)
                  ->after('activity_status')
                  ->comment('Time gap in seconds since last activity');
            $table->timestamp('last_activity_time')->nullable()
                  ->after('activity_time')
                  ->comment('Time of last detected activity');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['activity_status', 'inactive_gap', 'last_activity_time']);
        });
    }
};