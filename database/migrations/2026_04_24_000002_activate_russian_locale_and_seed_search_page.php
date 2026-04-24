<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->activateRussianLanguage();
        $this->seedSearchPage();
        $this->backfillStaticTranslations();
        $this->backfillModelTranslations();
        $this->backfillSeoQuestions();
    }

    public function down(): void
    {
        if (Schema::hasTable('languages')) {
            DB::table('languages')
                ->where('code', 'ru')
                ->update([
                    'is_active' => false,
                    'updated_at' => now(),
                ]);
        }

        if (Schema::hasTable('pages') && Schema::hasTable('page_translations')) {
            $pageId = DB::table('pages')->where('slug', 'search-cars')->value('id');

            if ($pageId) {
                DB::table('page_translations')->where('page_id', $pageId)->delete();
                DB::table('pages')->where('id', $pageId)->delete();
            }
        }
    }

    private function activateRussianLanguage(): void
    {
        if (!Schema::hasTable('languages')) {
            return;
        }

        DB::table('languages')->updateOrInsert(
            ['code' => 'ru'],
            [
                'name' => 'Russian',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function seedSearchPage(): void
    {
        if (!Schema::hasTable('pages') || !Schema::hasTable('page_translations')) {
            return;
        }

        $pageTranslationColumns = Schema::getColumnListing('page_translations');

        $pageId = DB::table('pages')->where('slug', 'search-cars')->value('id');

        if (!$pageId) {
            $pageId = DB::table('pages')->insertGetId([
                'name' => 'Search Cars',
                'slug' => 'search-cars',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $translations = [
            'en' => [
                'title' => 'Search Cars in Dubai',
                'description' => 'Use Afandina search to compare available rental cars by brand, model, category, and budget.',
                'article' => '<p>Search our active fleet and quickly move from discovery to booking. You can refine results by availability, brand, category, year, and pricing to find the right car for your trip.</p>',
                'meta_title' => 'Search Rental Cars | Afandina Car Rental',
                'meta_description' => 'Search rental cars by brand, model, category, and keyword with Afandina Car Rental.',
            ],
            'ar' => [
                'title' => 'ابحث عن سيارات للإيجار في دبي',
                'description' => 'استخدم بحث أفندينا لمقارنة السيارات المتاحة حسب العلامة والموديل والفئة والميزانية.',
                'article' => '<p>ابحث في أسطولنا النشط وانتقل بسرعة من الاستكشاف إلى الحجز. يمكنك تحسين النتائج حسب التوفر والعلامة والفئة والسنة والسعر للعثور على السيارة المناسبة لرحلتك.</p>',
                'meta_title' => 'بحث سيارات للإيجار | أفندينا لتأجير السيارات',
                'meta_description' => 'ابحث عن سيارات للإيجار حسب العلامة أو الموديل أو الفئة أو الكلمة المفتاحية مع أفندينا.',
            ],
            'ru' => [
                'title' => 'Поиск автомобилей в аренду в Дубае',
                'description' => 'Используйте поиск Afandina, чтобы сравнить доступные автомобили по марке, модели, категории и бюджету.',
                'article' => '<p>Ищите автомобили в активном автопарке и быстро переходите от выбора к бронированию. Фильтруйте результаты по наличию, марке, категории, году и цене, чтобы найти подходящий автомобиль для поездки.</p>',
                'meta_title' => 'Поиск автомобилей в аренду | Afandina Car Rental',
                'meta_description' => 'Ищите автомобили в аренду по марке, модели, категории и ключевым словам с Afandina Car Rental.',
            ],
        ];

        foreach ($translations as $locale => $translation) {
            $payload = [
                'page_id' => $pageId,
                'locale' => $locale,
                'sub_description' => null,
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
            ];

            foreach ($translation as $column => $value) {
                if (in_array($column, $pageTranslationColumns, true)) {
                    $payload[$column] = $value;
                }
            }

            if (in_array('meta_keywords', $pageTranslationColumns, true)) {
                $payload['meta_keywords'] = null;
            }

            DB::table('page_translations')->updateOrInsert(
                [
                    'page_id' => $pageId,
                    'locale' => $locale,
                ],
                array_intersect_key($payload, array_flip($pageTranslationColumns))
            );
        }
    }

    private function backfillStaticTranslations(): void
    {
        if (!Schema::hasTable('static_translations')) {
            return;
        }

        DB::table('static_translations')
            ->where('locale', 'en')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('static_translations')->updateOrInsert(
                        [
                            'key' => $row->key,
                            'locale' => 'ru',
                            'section' => $row->section,
                        ],
                        [
                            'value' => $row->value,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });
    }

    private function backfillModelTranslations(): void
    {
        $tables = [
            'template_translations' => 'template_id',
            'seo_question_translations' => 'seo_question_id',
            'brand_translations' => 'brand_id',
            'car_model_translations' => 'car_model_id',
            'category_translations' => 'category_id',
            'period_translations' => 'period_id',
            'gear_type_translations' => 'gear_type_id',
            'color_translations' => 'color_id',
            'body_style_translations' => 'body_style_id',
            'maker_translations' => 'maker_id',
            'feature_translations' => 'feature_id',
            'car_translations' => 'car_id',
            'location_translations' => 'location_id',
            'blog_translations' => 'blog_id',
            'faq_translations' => 'faq_id',
            'service_translations' => 'service_id',
            'document_translations' => 'document_id',
            'about_translations' => 'about_id',
            'home_translations' => 'home_id',
            'advertisement_translations' => 'advertisement_id',
            'short_video_translations' => 'short_video_id',
            'currency_translations' => 'currency_id',
            'page_translations' => 'page_id',
        ];

        foreach ($tables as $table => $foreignKey) {
            $this->backfillTranslationTable($table, $foreignKey);
        }
    }

    private function backfillTranslationTable(string $table, string $foreignKey): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $columns = Schema::getColumnListing($table);

        if (!in_array('locale', $columns, true) || !in_array($foreignKey, $columns, true) || !in_array('id', $columns, true)) {
            return;
        }

        DB::table($table)
            ->where('locale', 'en')
            ->orderBy('id')
            ->chunkById(100, function ($rows) use ($table, $foreignKey, $columns): void {
                foreach ($rows as $row) {
                    $exists = DB::table($table)
                        ->where($foreignKey, $row->{$foreignKey})
                        ->where('locale', 'ru')
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $payload = [];

                    foreach ($columns as $column) {
                        if ($column === 'id') {
                            continue;
                        }

                        $payload[$column] = $row->{$column} ?? null;
                    }

                    $payload['locale'] = 'ru';

                    if (array_key_exists('slug', $payload)) {
                        $payload['slug'] = null;
                    }

                    if (array_key_exists('created_at', $payload)) {
                        $payload['created_at'] = now();
                    }

                    if (array_key_exists('updated_at', $payload)) {
                        $payload['updated_at'] = now();
                    }

                    DB::table($table)->insert($payload);
                }
            });
    }

    private function backfillSeoQuestions(): void
    {
        if (!Schema::hasTable('seo_questions')) {
            return;
        }

        DB::table('seo_questions')
            ->where('locale', 'en')
            ->orderBy('id')
            ->chunkById(100, function ($rows): void {
                foreach ($rows as $row) {
                    $exists = DB::table('seo_questions')
                        ->where('seo_questionable_type', $row->seo_questionable_type)
                        ->where('seo_questionable_id', $row->seo_questionable_id)
                        ->where('locale', 'ru')
                        ->where('question_text', $row->question_text)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('seo_questions')->insert([
                        'seo_questionable_type' => $row->seo_questionable_type,
                        'seo_questionable_id' => $row->seo_questionable_id,
                        'locale' => 'ru',
                        'question_text' => $row->question_text,
                        'answer_text' => $row->answer_text,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }
};
