<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNullableColumnsInSensorDataTable extends Migration
{
    public function up()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->float('mq6_value')->nullable()->change();
            $table->float('mq8_value')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->float('mq6_value')->nullable(false)->change();
            $table->float('mq8_value')->nullable(false)->change();
        });
    }
}

