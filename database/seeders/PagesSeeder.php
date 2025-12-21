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
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Create home page
        $homePage = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'name' => 'Home',
                'is_active' => true,
            ]
        );

        // Create translations for home page
        foreach ($languages as $language) {
            DB::table('page_translations')->updateOrInsert(
                [
                    'page_id' => $homePage->id,
                    'locale' => $language->code,
                ],
                [
                    'title' => $this->getDefaultHomeTitle($language->code),
                    'description' => $this->getDefaultHomeDescription($language->code),
                    'sub_description' => $this->getDefaultHomeSubDescription($language->code),
                    // Section fields
                    'category_section_title' => $this->getDefaultCategorySectionTitle($language->code),
                    'category_section_description' => $this->getDefaultCategorySectionDescription($language->code),
                    'brands_section_title' => $this->getDefaultBrandsSectionTitle($language->code),
                    'brands_section_description' => $this->getDefaultBrandsSectionDescription($language->code),
                    'special_offers_title' => $this->getDefaultSpecialOffersTitle($language->code),
                    'special_offers_description' => $this->getDefaultSpecialOffersDescription($language->code),
                    'only_on_us_title' => $this->getDefaultOnlyOnUsTitle($language->code),
                    'only_on_us_description' => $this->getDefaultOnlyOnUsDescription($language->code),
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

    // Home page methods
    private function getDefaultHomeTitle($locale)
    {
        $titles = [
            'en' => 'Home',
            'ar' => 'الرئيسية',
            'fr' => 'Accueil',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultHomeDescription($locale)
    {
        $descriptions = [
            'en' => 'Welcome to our car rental service.',
            'ar' => 'مرحباً بكم في خدمة تأجير السيارات.',
            'fr' => 'Bienvenue dans notre service de location de voitures.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }

    private function getDefaultHomeSubDescription($locale)
    {
        $subDescriptions = [
            'en' => 'Find your perfect car for rent.',
            'ar' => 'ابحث عن سيارتك المثالية للتأجير.',
            'fr' => 'Trouvez la voiture parfaite à louer.',
        ];

        return $subDescriptions[$locale] ?? $subDescriptions['en'];
    }

    private function getDefaultCategorySectionTitle($locale)
    {
        $titles = [
            'en' => 'Browse by Category',
            'ar' => 'تصفح حسب الفئة',
            'fr' => 'Parcourir par catégorie',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultCategorySectionDescription($locale)
    {
        $descriptions = [
            'en' => 'Explore our wide range of car categories.',
            'ar' => 'استكشف مجموعتنا الواسعة من فئات السيارات.',
            'fr' => 'Explorez notre large gamme de catégories de voitures.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }

    private function getDefaultBrandsSectionTitle($locale)
    {
        $titles = [
            'en' => 'Our Brands',
            'ar' => 'علاماتنا التجارية',
            'fr' => 'Nos marques',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultBrandsSectionDescription($locale)
    {
        $descriptions = [
            'en' => 'Choose from top car brands available for rent.',
            'ar' => 'اختر من بين أفضل العلامات التجارية للسيارات المتاحة للتأجير.',
            'fr' => 'Choisissez parmi les meilleures marques de voitures disponibles à la location.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }

    private function getDefaultSpecialOffersTitle($locale)
    {
        $titles = [
            'en' => 'Special Offers',
            'ar' => 'عروض خاصة',
            'fr' => 'Offres spéciales',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultSpecialOffersDescription($locale)
    {
        $descriptions = [
            'en' => 'Check out our exclusive special offers and deals.',
            'ar' => 'اطلع على عروضنا الخاصة الحصرية والصفقات.',
            'fr' => 'Découvrez nos offres spéciales exclusives et nos offres.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }

    private function getDefaultOnlyOnUsTitle($locale)
    {
        $titles = [
            'en' => 'Only on Us',
            'ar' => 'متاح فقط لدينا',
            'fr' => 'Uniquement chez nous',
        ];

        return $titles[$locale] ?? $titles['en'];
    }

    private function getDefaultOnlyOnUsDescription($locale)
    {
        $descriptions = [
            'en' => 'Exclusive cars available only at our rental service.',
            'ar' => 'سيارات حصرية متاحة فقط في خدمة التأجير الخاصة بنا.',
            'fr' => 'Voitures exclusives disponibles uniquement dans notre service de location.',
        ];

        return $descriptions[$locale] ?? $descriptions['en'];
    }
}

