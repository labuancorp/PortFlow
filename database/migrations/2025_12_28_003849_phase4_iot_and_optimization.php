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
        // 1. IoT Telemetry for Assets
        Schema::table('port_assets', function (Blueprint $table) {
            $table->string('telemetry_id')->nullable()->after('identifier');
            $table->decimal('current_engine_hours', 10, 2)->default(0)->after('status');
            $table->enum('billing_mode', ['duration', 'telemetry'])->default('duration')->after('rate_per_day');
        });

        // 2. Engine Hour Tracking for Bookings
        Schema::table('asset_bookings', function (Blueprint $table) {
            $table->decimal('initial_engine_hours', 10, 2)->nullable()->after('check_in_media');
            $table->decimal('final_engine_hours', 10, 2)->nullable()->after('initial_engine_hours');
        });

        // 3. Smart Procurement Inventory
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('category')->default('General'); // Spares, Consumables, PPE
            $table->integer('current_stock')->default(0);
            $table->integer('min_threshold')->default(10);
            $table->string('unit')->default('pcs'); // pcs, liters, kg
            $table->string('location')->nullable(); // Warehouse A, Shelf 3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
        
        Schema::table('asset_bookings', function (Blueprint $table) {
            $table->dropColumn(['initial_engine_hours', 'final_engine_hours']);
        });
        
        Schema::table('port_assets', function (Blueprint $table) {
            $table->dropColumn(['telemetry_id', 'current_engine_hours', 'billing_mode']);
        });
    }
};
