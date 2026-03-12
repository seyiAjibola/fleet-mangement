<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('supplier_id');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('contact_person');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('city');
            $table->text('business_address');
            $table->integer('years_in_business');
            $table->string('instagram_page')->nullable();
            $table->string('website')->nullable();
            $table->string('status')->default('active');
            $table->integer('supplier_score')->default(0);
            $table->string('supplier_tier')->nullable();
            $table->timestamps();

            $table->index('city');
            $table->index('status');
            $table->index('supplier_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
