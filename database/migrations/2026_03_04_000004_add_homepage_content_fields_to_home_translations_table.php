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
            $table->longText('hero_title_prefix')->nullable();
            $table->longText('hero_title_highlight')->nullable();
            $table->longText('hero_title_suffix')->nullable();
            $table->longText('hero_banner_paragraph')->nullable();
            $table->longText('hero_customers_label')->nullable();
            $table->longText('hero_customers_subtitle')->nullable();
            $table->longText('hero_browse_cars_label')->nullable();
            $table->longText('hero_browse_blogs_label')->nullable();
            $table->longText('hero_starting_from_label')->nullable();
            $table->longText('hero_per_day_label')->nullable();
            $table->longText('hero_available_for_rent_label')->nullable();

            $table->longText('feature_section_title')->nullable();
            $table->longText('feature_section_paragraph')->nullable();
            $table->longText('feature_item_1_title')->nullable();
            $table->longText('feature_item_1_description')->nullable();
            $table->longText('feature_item_2_title')->nullable();
            $table->longText('feature_item_2_description')->nullable();
            $table->longText('feature_item_3_title')->nullable();
            $table->longText('feature_item_3_description')->nullable();
            $table->longText('feature_item_4_title')->nullable();
            $table->longText('feature_item_4_description')->nullable();
            $table->longText('feature_item_5_title')->nullable();
            $table->longText('feature_item_5_description')->nullable();
            $table->longText('feature_item_6_title')->nullable();
            $table->longText('feature_item_6_description')->nullable();

            $table->longText('rental_section_title')->nullable();
            $table->longText('rental_section_paragraph')->nullable();
            $table->longText('rental_step_1_title')->nullable();
            $table->longText('rental_step_1_description')->nullable();
            $table->longText('rental_step_2_title')->nullable();
            $table->longText('rental_step_2_description')->nullable();
            $table->longText('rental_step_3_title')->nullable();
            $table->longText('rental_step_3_description')->nullable();
            $table->longText('rental_stat_1_value')->nullable();
            $table->longText('rental_stat_1_suffix')->nullable();
            $table->longText('rental_stat_1_label')->nullable();
            $table->longText('rental_stat_2_value')->nullable();
            $table->longText('rental_stat_2_suffix')->nullable();
            $table->longText('rental_stat_2_label')->nullable();
            $table->longText('rental_stat_3_value')->nullable();
            $table->longText('rental_stat_3_suffix')->nullable();
            $table->longText('rental_stat_3_label')->nullable();
            $table->longText('rental_stat_4_value')->nullable();
            $table->longText('rental_stat_4_suffix')->nullable();
            $table->longText('rental_stat_4_label')->nullable();

            $table->longText('support_item_1_text')->nullable();
            $table->longText('support_item_2_text')->nullable();
            $table->longText('support_item_3_text')->nullable();
            $table->longText('support_item_4_text')->nullable();
            $table->longText('support_item_5_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_translations', function (Blueprint $table) {
            $table->dropColumn([
                'hero_title_prefix',
                'hero_title_highlight',
                'hero_title_suffix',
                'hero_banner_paragraph',
                'hero_customers_label',
                'hero_customers_subtitle',
                'hero_browse_cars_label',
                'hero_browse_blogs_label',
                'hero_starting_from_label',
                'hero_per_day_label',
                'hero_available_for_rent_label',
                'feature_section_title',
                'feature_section_paragraph',
                'feature_item_1_title',
                'feature_item_1_description',
                'feature_item_2_title',
                'feature_item_2_description',
                'feature_item_3_title',
                'feature_item_3_description',
                'feature_item_4_title',
                'feature_item_4_description',
                'feature_item_5_title',
                'feature_item_5_description',
                'feature_item_6_title',
                'feature_item_6_description',
                'rental_section_title',
                'rental_section_paragraph',
                'rental_step_1_title',
                'rental_step_1_description',
                'rental_step_2_title',
                'rental_step_2_description',
                'rental_step_3_title',
                'rental_step_3_description',
                'rental_stat_1_value',
                'rental_stat_1_suffix',
                'rental_stat_1_label',
                'rental_stat_2_value',
                'rental_stat_2_suffix',
                'rental_stat_2_label',
                'rental_stat_3_value',
                'rental_stat_3_suffix',
                'rental_stat_3_label',
                'rental_stat_4_value',
                'rental_stat_4_suffix',
                'rental_stat_4_label',
                'support_item_1_text',
                'support_item_2_text',
                'support_item_3_text',
                'support_item_4_text',
                'support_item_5_text',
            ]);
        });
    }
};
