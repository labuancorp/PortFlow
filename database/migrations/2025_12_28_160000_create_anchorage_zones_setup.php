<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anchorage_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('boundary_coordinates'); // GeoJSON or Array of [lat,lng]
            $table->integer('max_capacity')->default(5);
            $table->decimal('min_depth', 5, 2)->nullable();
            $table->string('status')->default('active'); // active, closed
            $table->timestamps();
        });

        Schema::table('port_calls', function (Blueprint $table) {
            $table->foreignId('anchorage_zone_id')->nullable()->constrained('anchorage_zones')->nullOnDelete();
            $table->timestamp('anchored_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('port_calls', function (Blueprint $table) {
            $table->dropForeign(['anchorage_zone_id']);
            $table->dropColumn(['anchorage_zone_id', 'anchored_at']);
        });

        Schema::dropIfExists('anchorage_zones');
    }
};
