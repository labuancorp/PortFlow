<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargo_manifests', function (Blueprint $table) {
            $table->boolean('yard_storage_requested')->default(false)->after('status');
            $table->string('preferred_zone_type')->nullable()->after('yard_storage_requested'); // general, dg, refrigerated
        });
    }

    public function down(): void
    {
        Schema::table('cargo_manifests', function (Blueprint $table) {
            $table->dropColumn(['yard_storage_requested', 'preferred_zone_type']);
        });
    }
};
