<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Reference Data (IMDG)
        Schema::create('dg_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_code')->unique(); // e.g. "1.1", "3", "8"
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('dg_segregation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_a_id')->constrained('dg_classes')->onDelete('cascade');
            $table->foreignId('class_b_id')->constrained('dg_classes')->onDelete('cascade');
            $table->string('rule'); // allowed, away_from (3m), separated (6m), prohibited
            $table->unique(['class_a_id', 'class_b_id']);
            $table->timestamps();
        });

        // 2. Declarations (Linked to Operations)
        Schema::create('dg_declarations', function (Blueprint $table) {
            $table->id();
            // Can be polymorphic linked to CargoManifestItem or InventoryItem
            // For simplicity, we'll link to an optional generic reference ID or just text
            $table->string('reference_type')->nullable(); // 'manifest', 'inventory'
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('un_number'); // e.g. UN1203
            $table->string('proper_shipping_name');
            $table->foreignId('dg_class_id')->constrained('dg_classes');
            $table->string('packing_group')->nullable(); // I, II, III
            $table->decimal('flash_point', 8, 2)->nullable();
            $table->decimal('neq_kg', 10, 4)->nullable(); // Net Explosive Quantity
            $table->text('emergency_contact')->nullable();
            
            $table->timestamps();
        });

        // 3. Specialized Storage (Bunker)
        Schema::create('explosive_bunker_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dg_declaration_id')->constrained('dg_declarations');
            $table->string('magazine_id'); // Identifier for the specific cell/magazine
            $table->decimal('quantity_stored', 10, 2);
            $table->date('expiry_date')->nullable();
            $table->string('security_seal_number')->nullable();
            $table->string('police_permit_number')->nullable();
            $table->string('status')->default('stored'); // stored, released, disposed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explosive_bunker_inventory');
        Schema::dropIfExists('dg_declarations');
        Schema::dropIfExists('dg_segregation_rules');
        Schema::dropIfExists('dg_classes');
    }
};
