<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type');
            $table->string('status_snapshot');
            $table->string('context_key');
            $table->json('channels')->nullable();
            $table->timestamp('notified_at');
            $table->timestamps();

            $table->unique(['compliance_record_id', 'user_id', 'notification_type', 'context_key'], 'compliance_notification_logs_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_notification_logs');
    }
};
