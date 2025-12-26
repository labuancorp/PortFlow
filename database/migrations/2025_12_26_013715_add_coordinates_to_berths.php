<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('berths', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('status');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('color', 20)->default('green')->after('longitude'); // green, yellow, blue
        });
    }

    public function down()
    {
        Schema::table('berths', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'color']);
        });
    }
};
