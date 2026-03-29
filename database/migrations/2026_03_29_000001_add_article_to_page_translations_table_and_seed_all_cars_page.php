<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('page_translations', 'article')) {
            Schema::table('page_translations', function (Blueprint $table) {
                $table->longText('article')->nullable();
            });
        }

        $pageId = DB::table('pages')->where('slug', 'all-cars')->value('id');

        if (!$pageId) {
            $pageId = DB::table('pages')->insertGetId([
                'name' => 'All Cars',
                'slug' => 'all-cars',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $languageCodes = DB::table('languages')->pluck('code');

        foreach ($languageCodes as $languageCode) {
            $translationExists = DB::table('page_translations')
                ->where('page_id', $pageId)
                ->where('locale', $languageCode)
                ->exists();

            if ($translationExists) {
                continue;
            }

            DB::table('page_translations')->insert([
                'page_id' => $pageId,
                'locale' => $languageCode,
                'title' => null,
                'description' => null,
                'sub_description' => null,
                'article' => null,
                'category_section_title' => null,
                'category_section_description' => null,
                'brands_section_title' => null,
                'brands_section_description' => null,
                'special_offers_title' => null,
                'special_offers_description' => null,
                'only_on_us_title' => null,
                'only_on_us_description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $pageId = DB::table('pages')->where('slug', 'all-cars')->value('id');

        if ($pageId) {
            DB::table('page_translations')->where('page_id', $pageId)->delete();
            DB::table('pages')->where('id', $pageId)->delete();
        }

        if (Schema::hasColumn('page_translations', 'article')) {
            Schema::table('page_translations', function (Blueprint $table) {
                $table->dropColumn('article');
            });
        }
    }
};
