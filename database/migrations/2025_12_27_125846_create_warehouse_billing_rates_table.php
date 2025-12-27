<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_billing_rates', function (Blueprint $table) {
            $table->id();
            $table->string('zone_type')->default('general'); // general, dg, refrigerated
            $table->decimal('rate_per_m3_per_day', 10, 2)->default(5.00); // RM 5 per m³ per day
            $table->decimal('dg_surcharge_percentage', 5, 2)->default(50.00); // 50% extra for DG
            $table->decimal('minimum_charge', 10, 2)->default(50.00); // Minimum daily charge
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default rates
        DB::table('warehouse_billing_rates')->insert([
            'zone_type' => 'general',
            'rate_per_m3_per_day' => 5.00,
            'dg_surcharge_percentage' => 50.00,
            'minimum_charge' => 50.00,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_billing_rates');
    }
};
