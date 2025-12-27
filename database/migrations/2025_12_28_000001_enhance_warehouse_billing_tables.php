<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_zones', 'type')) {
                $table->string('type')->default('general')->after('name'); // general, refrigerated, open_yard
            }
        });

        Schema::table('warehouse_billing_rates', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_billing_rates', 'bulk_discount_threshold_m3')) {
                $table->decimal('bulk_discount_threshold_m3', 10, 2)->default(100.00)->after('dg_surcharge_percentage');
                $table->decimal('bulk_discount_percentage', 5, 2)->default(10.00)->after('bulk_discount_threshold_m3');
                $table->integer('penalty_after_days')->default(30)->after('bulk_discount_percentage');
                $table->decimal('penalty_surcharge_percentage', 5, 2)->default(20.00)->after('penalty_after_days');
            }
        });
        
        // Update existing assets to have a link if needed, but for now we focus on zones.
    }

    public function down(): void
    {
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('warehouse_billing_rates', function (Blueprint $table) {
            $table->dropColumn([
                'bulk_discount_threshold_m3',
                'bulk_discount_percentage',
                'penalty_after_days',
                'penalty_surcharge_percentage'
            ]);
        });
    }
};
