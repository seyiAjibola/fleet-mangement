<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_bookings', function (Blueprint $table) {
            $table->bigIncrements('booking_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->timestamp('pickup_time');
            $table->string('vehicle_category');
            $table->string('booking_source');
            $table->unsignedBigInteger('assigned_vehicle')->nullable();
            $table->unsignedBigInteger('assigned_driver')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('vehicle_category');
            $table->index('status');
            $table->index('pickup_time');

            $table->foreign('assigned_vehicle')
                ->references('vehicle_id')
                ->on('vehicles')
                ->nullOnDelete()
                ->onUpdate('cascade');

            $table->foreign('assigned_driver')
                ->references('driver_id')
                ->on('drivers')
                ->nullOnDelete()
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_bookings');
    }
};
