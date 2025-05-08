<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->json('dismissed_alerts')->nullable()->default(json_encode([])); // Store dismissed alerts as JSON
        });
    }

    public function down()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn('dismissed_alerts');
        });
    }

};
