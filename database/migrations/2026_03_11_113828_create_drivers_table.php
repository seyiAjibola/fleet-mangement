<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('driver_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('driver_name');
            $table->string('phone_number');
            $table->string('license_number');
            $table->integer('years_experience');
            $table->string('languages');
            $table->text('professional_experience')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('supplier_id');
            $table->index('vehicle_id');
            $table->index('status');
            $table->unique('license_number');

            $table->foreign('supplier_id')
                ->references('supplier_id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('vehicle_id')
                ->references('vehicle_id')
                ->on('vehicles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
