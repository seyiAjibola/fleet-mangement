<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('supplier_id')->constrained('users')->nullOnDelete();
            $table->index('created_by_user_id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('vehicle_id')->constrained('users')->nullOnDelete();
            $table->index('created_by_user_id');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('driver_id')->constrained('users')->nullOnDelete();
            $table->index('created_by_user_id');
        });

        Schema::table('customer_bookings', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('booking_id')->constrained('users')->nullOnDelete();
            $table->index('created_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('customer_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_user_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_user_id');
        });
    }
};
