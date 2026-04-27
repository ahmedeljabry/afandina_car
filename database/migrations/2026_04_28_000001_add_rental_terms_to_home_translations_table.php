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
        Schema::table('home_translations', function (Blueprint $table) {
            $table->longText('mileage_policy')->nullable()->after('faq_section_paragraph');
            $table->longText('fuel_policy')->nullable()->after('mileage_policy');
            $table->longText('deposit_policy')->nullable()->after('fuel_policy');
            $table->longText('rental_policy')->nullable()->after('deposit_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_translations', function (Blueprint $table) {
            $table->dropColumn([
                'mileage_policy',
                'fuel_policy',
                'deposit_policy',
                'rental_policy',
            ]);
        });
    }
};
