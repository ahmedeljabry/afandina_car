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
        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale');  // e.g., 'en', 'ar', 'fr'
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('sub_description')->nullable();
            // Section titles and descriptions (for home page)
            $table->string('category_section_title')->nullable();
            $table->longText('category_section_description')->nullable();
            $table->string('brands_section_title')->nullable();
            $table->longText('brands_section_description')->nullable();
            $table->string('special_offers_title')->nullable();
            $table->longText('special_offers_description')->nullable();
            $table->string('only_on_us_title')->nullable();
            $table->longText('only_on_us_description')->nullable();
            // SEO fields
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();
            
            $table->unique(['page_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_translations');
    }
};

