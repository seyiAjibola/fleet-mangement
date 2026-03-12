<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('vehicle_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->integer('vehicle_year');
            $table->string('vehicle_color');
            $table->string('plate_number')->unique();
            $table->string('vehicle_category');
            $table->integer('passenger_capacity');
            $table->string('vehicle_condition');
            $table->boolean('air_condition')->default(false);
            $table->string('vehicle_location');
            $table->string('status')->default('available');
            $table->timestamps();

            $table->index('supplier_id');
            $table->index('vehicle_category');
            $table->index('status');
            $table->index('vehicle_location');

            $table->foreign('supplier_id')
                ->references('supplier_id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
