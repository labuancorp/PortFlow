<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['authority', 'agent', 'client', 'vendor']);
            $table->string('code', 50)->unique()->nullable();
            $table->text('billing_address')->nullable();
            $table->timestamps();
        });

        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('name');
            $table->string('imo_number', 20)->unique();
            $table->string('flag_country', 50)->nullable();
            $table->decimal('loa_meters', 5, 2);
            $table->decimal('draft_meters', 4, 2);
            $table->string('vessel_type', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('berths', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 20)->nullable();
            $table->decimal('max_loa', 5, 2)->nullable();
            $table->decimal('max_draft', 4, 2)->nullable();
            $table->enum('status', ['active', 'maintenance', 'occupied'])->default('active');
            $table->timestamps();
        });

        Schema::create('port_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained('vessels');
            $table->foreignId('agent_id')->constrained('organizations');
            $table->enum('status', ['requested', 'approved', 'anchored', 'alongside', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('eta')->nullable();
            $table->timestamp('etd')->nullable();
            $table->timestamp('ata')->nullable();
            $table->timestamp('atb')->nullable();
            $table->timestamp('atd')->nullable();
            $table->foreignId('assigned_berth_id')->nullable()->constrained('berths');
            $table->string('reference_no', 50)->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_call_id')->constrained('port_calls');
            $table->enum('service_type', ['water', 'fuel', 'crane', 'forklift', 'crew_change']);
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->enum('status', ['pending', 'delivered'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('port_calls');
        Schema::dropIfExists('berths');
        Schema::dropIfExists('vessels');
        Schema::dropIfExists('organizations');
    }
};
