<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('devices', function (Blueprint $table) {
        $table->string('room_name')->after('id'); // atau ->after('id') jika ingin paling awal
    });
}

public function down()
{
    Schema::table('devices', function (Blueprint $table) {
        $table->dropColumn('room_name');
    });
}
};
