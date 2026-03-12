<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customer_bookings', function (Blueprint $table) {
            $table->timestamp('dropoff_time')->nullable()->after('pickup_time');
            $table->index('dropoff_time');
        });
    }

    public function down(): void
    {
        Schema::table('customer_bookings', function (Blueprint $table) {
            $table->dropIndex(['dropoff_time']);
            $table->dropColumn('dropoff_time');
        });
    }
};
