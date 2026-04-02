<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('cac_no')->nullable()->after('phone_number');
            $table->string('tin')->nullable()->after('cac_no');
            $table->index('cac_no');
            $table->index('tin');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['cac_no']);
            $table->dropIndex(['tin']);
            $table->dropColumn(['cac_no', 'tin']);
        });
    }
};
