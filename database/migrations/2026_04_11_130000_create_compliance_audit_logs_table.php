<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('summary');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['compliance_record_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_audit_logs');
    }
};
