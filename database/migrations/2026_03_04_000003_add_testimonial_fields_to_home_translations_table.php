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
            $table->longText('testimonial_section_title')->nullable();
            $table->longText('testimonial_section_paragraph')->nullable();
            $table->longText('testimonial_review_1')->nullable();
            $table->longText('testimonial_client_1_name')->nullable();
            $table->longText('testimonial_client_1_location')->nullable();
            $table->longText('testimonial_review_2')->nullable();
            $table->longText('testimonial_client_2_name')->nullable();
            $table->longText('testimonial_client_2_location')->nullable();
            $table->longText('testimonial_review_3')->nullable();
            $table->longText('testimonial_client_3_name')->nullable();
            $table->longText('testimonial_client_3_location')->nullable();
            $table->longText('testimonial_cta_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_translations', function (Blueprint $table) {
            $table->dropColumn([
                'testimonial_section_title',
                'testimonial_section_paragraph',
                'testimonial_review_1',
                'testimonial_client_1_name',
                'testimonial_client_1_location',
                'testimonial_review_2',
                'testimonial_client_2_name',
                'testimonial_client_2_location',
                'testimonial_review_3',
                'testimonial_client_3_name',
                'testimonial_client_3_location',
                'testimonial_cta_label',
            ]);
        });
    }
};
