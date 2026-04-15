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
        Schema::create('compliance_records', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('compliance_type_id')
                ->constrained()
                ->cascadeOnDelete();
        
            // Polymorphic relation
            $table->string('entity_type'); // App\Models\Vehicle, App\Models\Driver
            $table->unsignedBigInteger('entity_id');
        
            $table->string('document_number')->nullable();
        
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
        
            $table->string('status')->default('valid'); 
            // valid, expiring, expired, non_compliant
        
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
        
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        
            $table->timestamps();
        
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_records');
    }
};
