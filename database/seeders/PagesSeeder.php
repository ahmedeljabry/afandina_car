<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active languages
        $languages = Language::where('is_active', 1)->get();

        if ($languages->isEmpty()) {
            $this->command->warn('No active languages found. Please seed languages first.');
            return;
        }

        // Create contact page
        $contactPage = Page::firstOrCreate(
            ['slug' => 'contact'],
            [
                'name' => 'Contact',
                'is_active' => true,
            ]
        );

        // Create translations for contact page
        foreach ($languages as $language) {
            DB::table('page_translations')->updateOrInsert(
                [
                    'page_id' => $contactPage->id,
                    'locale' => $language->code,
                ],
                [
                    'title' => $this->getDefaultTitle($language->code),
                    'description' => $this->getDefaultDescription($language->code),
                    'sub_description' => $this->getDefaultSubDescription($language->code),
                    // SEO fields
                    'meta_title' => $this->getDefaultMetaTitle($language->code),
                    'meta_description' => $this->getDefaultMetaDescription($language->code),
                    'meta_keywords' => 'contact, get in touch, contact us',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Pages seeded successfully!');
    }

    private function getDefaultTitle($locale)
    {
        $titles = [
            'en' => 'Contact Us',
            'ar' => 'اتصل بنا',
            'fr' => 'Contactez-nous',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultDescription($locale)
    {
        $descriptions = [
            'en' => 'Get in touch with us. We are here to help you with any questions or inquiries.',
            'ar' => 'تواصل معنا. نحن هنا لمساعدتك في أي أسئلة أو استفسارات.',
            'fr' => 'Contactez-nous. Nous sommes là pour vous aider avec toutes vos questions ou demandes.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }

    private function getDefaultSubDescription($locale)
    {
        $subDescriptions = [
            'en' => 'Fill out the form below and we will get back to you as soon as possible.',
            'ar' => 'املأ النموذج أدناه وسنعود إليك في أقرب وقت ممكن.',
            'fr' => 'Remplissez le formulaire ci-dessous et nous vous répondrons dès que possible.',
        ];

        return $subDescriptions[$locale] ?? $subDescriptions['en'];
    }

    private function getDefaultMetaTitle($locale)
    {
        $metaTitles = [
            'en' => 'Contact Us - Get in Touch',
            'ar' => 'اتصل بنا - تواصل معنا',
            'fr' => 'Contactez-nous - Restez en contact',
        ];

        return $metaTitles[$locale] ?? $metaTitles['en'];
    }

    private function getDefaultMetaDescription($locale)
    {
        $metaDescriptions = [
            'en' => 'Contact us for any inquiries. We are ready to help you with your car rental needs.',
            'ar' => 'اتصل بنا لأي استفسارات. نحن مستعدون لمساعدتك في احتياجات تأجير السيارات الخاصة بك.',
            'fr' => 'Contactez-nous pour toute demande. Nous sommes prêts à vous aider avec vos besoins de location de voiture.',
        ];

        return $metaDescriptions[$locale] ?? $metaDescriptions['en'];
    }
}

