<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;


class LocationSeeder extends Seeder
{
    protected $translator;

    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('locations')->truncate();
        DB::table('location_translations')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $locations = [
            [
                'name' => 'Al Barsha',
                'description' => 'A vibrant residential and commercial hub in Dubai, known for its malls and easy accessibility.',
            ],
            [
                'name' => 'Dubai Mall',
                'description' => 'The largest shopping mall in the world, located near Burj Khalifa, offering endless attractions.',
            ],
            [
                'name' => 'Business Bay',
                'description' => 'A bustling business district with modern skyscrapers and a vibrant lifestyle.',
            ],
            [
                'name' => 'Jumeirah',
                'description' => 'A picturesque coastal area known for beaches, luxury villas, and tourist attractions.',
            ],
            [
                'name' => 'Deira',
                'description' => 'A historical trading hub with vibrant souks and a glimpse of Dubai’s cultural heritage.',
            ],
            [
                'name' => 'Al Nahda',
                'description' => 'A family-friendly neighborhood with parks, schools, and convenient amenities.',
            ],
            [
                'name' => 'Downtown Dubai',
                'description' => 'The heart of Dubai, home to Burj Khalifa and other iconic landmarks.',
            ],
            [
                'name' => 'Al Wasl',
                'description' => 'A peaceful residential area offering proximity to city attractions and lush greenery.',
            ],
            [
                'name' => 'Yas Island',
                'description' => 'An entertainment hub in Abu Dhabi featuring world-class attractions like Ferrari World.',
            ],
            [
                'name' => 'Khalifa City',
                'description' => 'A quiet residential area ideal for families, located in Abu Dhabi.',
            ],
            [
                'name' => 'Al Reem Island',
                'description' => 'A modern residential and commercial area in Abu Dhabi with stunning waterfront views.',
            ],
            [
                'name' => 'Corniche',
                'description' => 'A scenic promenade in Abu Dhabi with beaches, parks, and recreational areas.',
            ],
            [
                'name' => 'Al Falah',
                'description' => 'A family-oriented community in Abu Dhabi with modern infrastructure.',
            ],
            [
                'name' => 'Sharjah Corniche',
                'description' => 'A peaceful waterfront in Sharjah, ideal for relaxation and leisure.',
            ],
            [
                'name' => 'Al Qasimia',
                'description' => 'A bustling neighborhood in Sharjah known for its cultural and shopping experiences.',
            ],
            [
                'name' => 'Muwaileh',
                'description' => 'A growing area in Sharjah, popular for its educational institutions and family-friendly vibe.',
            ],
            [
                'name' => 'Ajman Corniche',
                'description' => 'A picturesque coastal area in Ajman with beaches and relaxing vibes.',
            ],
            [
                'name' => 'Al Nuaimiya',
                'description' => 'A vibrant neighborhood in Ajman offering affordable living and local amenities.',
            ],
            [
                'name' => 'Umm Al Quwain Corniche',
                'description' => 'A serene coastal promenade offering tranquil views and outdoor activities.',
            ],
            [
                'name' => 'Al Rams',
                'description' => 'A small fishing village in Ras Al Khaimah with natural beauty and a calm environment.',
            ],
            [
                'name' => 'RAK Corniche',
                'description' => 'A scenic waterfront area in Ras Al Khaimah, ideal for family outings and leisure activities.',
            ],
        ];

        $languages = Language::get();

        foreach ($locations as $location) {
            $newLocation = Location::create(['is_active' => true]);

            foreach ($languages as $language) {
                $translatedData = $this->translateContent($location, $language->code);
                $slug = $this->generateUniqueSlug($translatedData['name'], $language->code);

                $newLocation->translations()->create([
                    'locale' => $language->code,
                    'name' => $translatedData['name'],
                    'slug' => $slug,
                    'description' => $translatedData['description'],
                ]);
            }
        }
    }

    /**
     * Translate content into the specified locale.
     */
    private function translateContent($location, $locale)
    {
        $translatedName = $this->translateText($location['name'], $locale);
        $translatedDescription = $this->translateText($location['description'], $locale);

        return [
            'name' => $translatedName,
            'description' => $translatedDescription,
        ];
    }

    /**
     * Translate text into the specified locale using GoogleTranslate.
     */
    private function translateText($text, $locale)
    {
        try {
            $translator = new GoogleTranslate($locale);
            return $translator->translate($text);
        } catch (\Exception $e) {
            $this->command->error("Translation failed for '$text': " . $e->getMessage());
            return $text; // Fallback to original text
        }
    }

    /**
     * Generate a unique slug by checking for conflicts and appending random characters if needed.
     */
    private function generateUniqueSlug($name, $locale)
    {
        $slug = Str::slug($name) . '-' . $locale;
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists in the database.
     */
    private function slugExists($slug)
    {
        return \DB::table('location_translations')->where('slug', $slug)->exists();
    }
}
