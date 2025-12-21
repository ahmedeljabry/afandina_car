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
        Schema::table('page_translations', function (Blueprint $table) {
            // Section titles and descriptions
            $table->string('category_section_title')->nullable()->after('sub_description');
            $table->longText('category_section_description')->nullable()->after('category_section_title');
            $table->string('brands_section_title')->nullable()->after('category_section_description');
            $table->longText('brands_section_description')->nullable()->after('brands_section_title');
            $table->string('special_offers_title')->nullable()->after('brands_section_description');
            $table->longText('special_offers_description')->nullable()->after('special_offers_title');
            $table->string('only_on_us_title')->nullable()->after('special_offers_description');
            $table->longText('only_on_us_description')->nullable()->after('only_on_us_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->dropColumn([
                'category_section_title',
                'category_section_description',
                'brands_section_title',
                'brands_section_description',
                'special_offers_title',
                'special_offers_description',
                'only_on_us_title',
                'only_on_us_description',
            ]);
        });
    }
};

