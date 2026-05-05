<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const RUSSIAN_ABOUT_CONTENT = [
        'about_main_header_title' => 'О нас',
        'about_our_agency_title' => 'О нашей компании',
        'about_main_header_paragraph' => 'С момента запуска в 1999 году агентство, объединяющее две ведущие службы проката автомобилей (FASTER и VIP), на протяжении многих лет опиралось на ключевые принципы: отличное соотношение цены и качества и сильную поддержку клиентов. Компания из Дубая значительно выросла по всем Эмиратам и смогла стать лидером в своей сфере благодаря целеустремленности и профессиональной команде.',
        'why_choose_title' => 'Почему выбирают нас?',
        'why_choose_content' => 'Мы предлагаем надежный сервис аренды авто с прозрачными тарифами, качественным автопарком и поддержкой на каждом этапе поездки. Наша команда помогает подобрать подходящий автомобиль для деловых поездок, отдыха и особых случаев.',
        'our_vision_title' => 'Наше видение',
        'our_vision_content' => 'Предоставлять надежную мобильность с премиальным клиентским опытом, чтобы аренда автомобиля в Дубае была простой, понятной и комфортной.',
        'our_mission_title' => 'Наша миссия',
        'our_mission_content' => 'Обеспечивать клиентов качественными автомобилями, гибкими условиями аренды и оперативной поддержкой для каждой поездки.',
        'meta_title' => 'О нас',
        'meta_description' => 'Узнайте больше о компании Afandina, нашем видении, миссии и сервисе аренды автомобилей в Дубае.',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('abouts') || !Schema::hasTable('about_translations')) {
            return;
        }

        $columns = Schema::getColumnListing('about_translations');
        $columnMap = array_flip($columns);

        DB::table('abouts')
            ->orderBy('id')
            ->chunkById(100, function ($abouts) use ($columns, $columnMap): void {
                foreach ($abouts as $about) {
                    $english = DB::table('about_translations')
                        ->where('about_id', $about->id)
                        ->where('locale', 'en')
                        ->first();

                    $russian = DB::table('about_translations')
                        ->where('about_id', $about->id)
                        ->where('locale', 'ru')
                        ->first();

                    if (!$english && !$russian) {
                        continue;
                    }

                    $payload = [
                        'about_id' => $about->id,
                        'locale' => 'ru',
                    ];

                    foreach (self::RUSSIAN_ABOUT_CONTENT as $field => $value) {
                        if (isset($columnMap[$field])) {
                            $payload[$field] = $value;
                        }
                    }

                    if (isset($columnMap['meta_keywords'])) {
                        $payload['meta_keywords'] = json_encode([
                            ['value' => 'о нас'],
                            ['value' => 'компания'],
                            ['value' => 'видение'],
                            ['value' => 'миссия'],
                            ['value' => 'аренда автомобилей'],
                        ], JSON_UNESCAPED_UNICODE);
                    }

                    if (isset($columnMap['created_at'])) {
                        $payload['created_at'] = now();
                    }

                    if (isset($columnMap['updated_at'])) {
                        $payload['updated_at'] = now();
                    }

                    $payload = array_intersect_key($payload, $columnMap);

                    if (!$russian) {
                        DB::table('about_translations')->insert($payload);
                        continue;
                    }

                    $updates = [];

                    foreach ($payload as $field => $value) {
                        if (in_array($field, ['about_id', 'locale', 'created_at'], true)) {
                            continue;
                        }

                        if ($this->shouldReplace($russian->{$field} ?? null, $english->{$field} ?? null)) {
                            $updates[$field] = $value;
                        }
                    }

                    if ($updates !== []) {
                        if (isset($columnMap['updated_at'])) {
                            $updates['updated_at'] = now();
                        }

                        DB::table('about_translations')
                            ->where('id', $russian->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        // Intentionally keep translated content that may have been edited after deployment.
    }

    private function shouldReplace($current, $english): bool
    {
        $currentText = trim(strip_tags((string) $current));
        $englishText = trim(strip_tags((string) $english));

        if ($currentText === '') {
            return true;
        }

        if ($englishText !== '' && $currentText === $englishText) {
            return true;
        }

        return in_array($currentText, [
            'About Us',
            'About Our Agency',
            'Why Choose Us',
            'Why Choose Us?',
            'Our Vision',
            'Our Mission',
            'Learn more about our company, our vision, and mission.',
        ], true);
    }
};
