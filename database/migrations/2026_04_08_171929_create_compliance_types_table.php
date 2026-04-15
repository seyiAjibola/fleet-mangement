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
        Schema::create('compliance_types', function (Blueprint $table) {
            $table->id();
        
            $table->string('name');
            $table->string('entity_type'); // vehicle, driver, supplier
        
            $table->boolean('expiry_required')->default(true);
        
            $table->unsignedInteger('notification_days_before')->default(3);
            $table->unsignedInteger('grace_period_days')->default(5);
        
            $table->boolean('is_active')->default(true);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_types');
    }
};
