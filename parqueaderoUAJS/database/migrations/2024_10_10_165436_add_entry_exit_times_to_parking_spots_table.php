<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parking_spots', function (Blueprint $table) {
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('parking_spots', function (Blueprint $table) {
            $table->dropColumn('entry_time');
            $table->dropColumn('exit_time');
        });
    }
};
