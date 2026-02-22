<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Gear_type;
use App\Models\Home;
use App\Models\Year;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeDataSeeder extends Seeder
{
    private array $columnsCache = [];

    public function run(): void
    {
        DB::transaction(function (): void {
            $categoryIds = $this->ensureCategoryIds(3);
            $brandIds = $this->ensureBrandIds(3);
            $colorId = $this->ensureColorId();
            $gearTypeId = $this->ensureGearTypeId();
            $yearId = $this->ensureYearId();

            $this->seedHomeSettings();

            $carIds = $this->seedCars(
                $categoryIds,
                $brandIds,
                $colorId,
                $gearTypeId,
                $yearId
            );

            $this->seedBlogs($carIds);
            $this->seedFaqs();
        });
    }

    private function seedHomeSettings(): void
    {
        $now = now();

        $homePayload = $this->tableData('homes', [
            'page_name' => 'Home Page',
            'hero_header_video_path' => '/videos/hero-banner.mp4',
            'hero_header_image_path' => 'website/assets/img/banner/banner.png',
            'hero_type' => 'image',
            'slug' => 'home',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $home = Home::query()->where('is_active', true)->first();

        if ($home) {
            $home->update($homePayload);
        } else {
            $home = Home::query()->create($homePayload);
        }

        $translations = [
            'en' => [
                'hero_header_title' => 'Explore verified and professional cars',
                'category_section_title' => 'Browse By Category',
                'category_section_paragraph' => 'Find the right vehicle type for your next trip.',
                'brand_section_title' => 'Top Car Brands',
                'brand_section_paragraph' => 'Choose from trusted brands with proven reliability.',
                'car_only_section_title' => 'Popular Cars',
                'car_only_section_paragraph' => 'These cars are among the most requested by customers.',
                'featured_cars_section_title' => 'Featured Cars',
                'featured_cars_section_paragraph' => 'Handpicked cars with great value and availability.',
                'faq_section_title' => 'Frequently Asked Questions',
                'faq_section_paragraph' => 'Answers to common booking and rental questions.',
                'blog_section_title' => 'Latest Blogs',
                'blog_section_paragraph' => 'Tips and guides to help you plan your next drive.',
                'where_find_us_section_title' => 'Locations',
                'where_find_us_section_paragraph' => 'Find pickup points across the city.',
                'contact_us_title' => 'Contact Our Team',
                'contact_us_paragraph' => 'Reach out for custom offers, fleet requests, and support.',
                'contact_us_detail_title' => 'Contact Details',
                'contact_us_detail_paragraph' => 'Available every day to support your booking journey.',
                'footer_section_paragraph' => 'Rent premium cars with transparent pricing, fast support, and flexible plans.',
                'meta_title' => 'Home - Afandina Car Rental',
                'meta_description' => 'Rent featured cars from trusted brands with flexible plans.',
                'meta_keywords' => $this->keywords(['car rental', 'featured cars', 'book car online']),
                'robots_index' => 'index',
                'robots_follow' => 'follow',
                'slug' => 'home-page-' . $home->id,
            ],
            'ar' => [
                'hero_header_title' => 'Explore verified and professional cars',
                'category_section_title' => 'Browse By Category',
                'category_section_paragraph' => 'Find the right vehicle type for your next trip.',
                'brand_section_title' => 'Top Car Brands',
                'brand_section_paragraph' => 'Choose from trusted brands with proven reliability.',
                'car_only_section_title' => 'Popular Cars',
                'car_only_section_paragraph' => 'These cars are among the most requested by customers.',
                'featured_cars_section_title' => 'Featured Cars',
                'featured_cars_section_paragraph' => 'Handpicked cars with great value and availability.',
                'faq_section_title' => 'Frequently Asked Questions',
                'faq_section_paragraph' => 'Answers to common booking and rental questions.',
                'blog_section_title' => 'Latest Blogs',
                'blog_section_paragraph' => 'Tips and guides to help you plan your next drive.',
                'where_find_us_section_title' => 'Locations',
                'where_find_us_section_paragraph' => 'Find pickup points across the city.',
                'contact_us_title' => 'Contact Our Team',
                'contact_us_paragraph' => 'Reach out for custom offers, fleet requests, and support.',
                'contact_us_detail_title' => 'Contact Details',
                'contact_us_detail_paragraph' => 'Available every day to support your booking journey.',
                'footer_section_paragraph' => 'Rent premium cars with transparent pricing, fast support, and flexible plans.',
                'meta_title' => 'Home - Afandina Car Rental',
                'meta_description' => 'Rent featured cars from trusted brands with flexible plans.',
                'meta_keywords' => $this->keywords(['car rental', 'featured cars', 'book car online']),
                'robots_index' => 'index',
                'robots_follow' => 'follow',
                'slug' => 'home-page-' . $home->id . '-ar',
            ],
        ];

        foreach ($translations as $locale => $translation) {
            $payload = $this->tableData('home_translations', array_merge([
                'home_id' => $home->id,
                'locale' => $locale,
                'created_at' => $now,
                'updated_at' => $now,
            ], $translation));

            DB::table('home_translations')->updateOrInsert(
                ['home_id' => $home->id, 'locale' => $locale],
                $payload
            );
        }
    }

    private function seedCars(
        array $categoryIds,
        array $brandIds,
        int $colorId,
        int $gearTypeId,
        int $yearId
    ): array {
        $now = now();

        $cars = [
            [
                'key' => 'mercedes-c-class',
                'name' => 'Mercedes C Class',
                'description' => 'A refined sedan with premium comfort for city and highway driving.',
                'daily_main_price' => 520,
                'daily_discount_price' => 480,
                'weekly_main_price' => 3300,
                'weekly_discount_price' => 2990,
                'monthly_main_price' => 11500,
                'monthly_discount_price' => 10200,
                'passenger_capacity' => 5,
                'door_count' => 4,
                'luggage_capacity' => 3,
                'image' => 'https://picsum.photos/seed/home-demo-car-1/1280/720',
            ],
            [
                'key' => 'bmw-x5',
                'name' => 'BMW X5',
                'description' => 'Spacious SUV balancing luxury, performance, and practicality.',
                'daily_main_price' => 640,
                'daily_discount_price' => 590,
                'weekly_main_price' => 4100,
                'weekly_discount_price' => 3750,
                'monthly_main_price' => 14200,
                'monthly_discount_price' => 12900,
                'passenger_capacity' => 5,
                'door_count' => 4,
                'luggage_capacity' => 4,
                'image' => 'https://picsum.photos/seed/home-demo-car-2/1280/720',
            ],
            [
                'key' => 'audi-a6',
                'name' => 'Audi A6',
                'description' => 'Executive-class comfort with a smooth and confident ride.',
                'daily_main_price' => 560,
                'daily_discount_price' => 510,
                'weekly_main_price' => 3500,
                'weekly_discount_price' => 3200,
                'monthly_main_price' => 12100,
                'monthly_discount_price' => 10900,
                'passenger_capacity' => 5,
                'door_count' => 4,
                'luggage_capacity' => 3,
                'image' => 'https://picsum.photos/seed/home-demo-car-3/1280/720',
            ],
            [
                'key' => 'range-rover-sport',
                'name' => 'Range Rover Sport',
                'description' => 'Premium SUV with strong road presence and high comfort.',
                'daily_main_price' => 760,
                'daily_discount_price' => 690,
                'weekly_main_price' => 4900,
                'weekly_discount_price' => 4450,
                'monthly_main_price' => 16800,
                'monthly_discount_price' => 15200,
                'passenger_capacity' => 5,
                'door_count' => 4,
                'luggage_capacity' => 4,
                'image' => 'https://picsum.photos/seed/home-demo-car-4/1280/720',
            ],
            [
                'key' => 'porsche-911',
                'name' => 'Porsche 911',
                'description' => 'Iconic sports car built for precise handling and excitement.',
                'daily_main_price' => 950,
                'daily_discount_price' => 880,
                'weekly_main_price' => 6200,
                'weekly_discount_price' => 5700,
                'monthly_main_price' => 21400,
                'monthly_discount_price' => 19900,
                'passenger_capacity' => 2,
                'door_count' => 2,
                'luggage_capacity' => 2,
                'image' => 'https://picsum.photos/seed/home-demo-car-5/1280/720',
            ],
            [
                'key' => 'toyota-land-cruiser',
                'name' => 'Toyota Land Cruiser',
                'description' => 'Reliable full-size SUV suitable for long trips and family travel.',
                'daily_main_price' => 610,
                'daily_discount_price' => 560,
                'weekly_main_price' => 3900,
                'weekly_discount_price' => 3560,
                'monthly_main_price' => 13500,
                'monthly_discount_price' => 12200,
                'passenger_capacity' => 7,
                'door_count' => 4,
                'luggage_capacity' => 5,
                'image' => 'https://picsum.photos/seed/home-demo-car-6/1280/720',
            ],
        ];

        $createdCarIds = [];
        foreach ($cars as $index => $item) {
            $slug = 'home-demo-' . $item['key'];
            $carId = (int) (DB::table('car_translations')->where('slug', $slug)->value('car_id') ?? 0);

            $carPayload = $this->tableData('cars', [
                'daily_main_price' => $item['daily_main_price'],
                'daily_discount_price' => $item['daily_discount_price'],
                'weekly_main_price' => $item['weekly_main_price'],
                'weekly_discount_price' => $item['weekly_discount_price'],
                'monthly_main_price' => $item['monthly_main_price'],
                'monthly_discount_price' => $item['monthly_discount_price'],
                'daily_mileage_included' => 250,
                'weekly_mileage_included' => 1400,
                'monthly_mileage_included' => 6000,
                'door_count' => $item['door_count'],
                'luggage_capacity' => $item['luggage_capacity'],
                'passenger_capacity' => $item['passenger_capacity'],
                'insurance_included' => true,
                'free_delivery' => true,
                'is_featured' => true,
                'is_flash_sale' => true,
                'only_on_afandina' => true,
                'is_active' => true,
                'status' => 'available',
                'brand_id' => $brandIds[$index % count($brandIds)],
                'category_id' => $categoryIds[$index % count($categoryIds)],
                'color_id' => $colorId,
                'gear_type_id' => $gearTypeId,
                'car_model_id' => null,
                'year_id' => $yearId,
                'default_image_path' => $item['image'],
                'default_thumbnail_path' => $item['image'],
                'crypto_payment_accepted' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($carId > 0) {
                DB::table('cars')->where('id', $carId)->update($carPayload);
            } else {
                $carId = DB::table('cars')->insertGetId($carPayload);
            }

            $createdCarIds[] = $carId;

            $translationBase = [
                'name' => $item['name'],
                'description' => $item['description'],
                'long_description' => $item['description'],
                'meta_title' => $item['name'] . ' rental',
                'meta_description' => $item['description'],
                'meta_keywords' => $this->keywords([$item['name'], 'car rental', 'book now']),
                'robots_index' => 'index',
                'robots_follow' => 'follow',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('car_translations')->updateOrInsert(
                ['car_id' => $carId, 'locale' => 'en'],
                $this->tableData('car_translations', array_merge($translationBase, [
                    'car_id' => $carId,
                    'locale' => 'en',
                    'slug' => $slug,
                ]))
            );

            DB::table('car_translations')->updateOrInsert(
                ['car_id' => $carId, 'locale' => 'ar'],
                $this->tableData('car_translations', array_merge($translationBase, [
                    'car_id' => $carId,
                    'locale' => 'ar',
                    'slug' => $slug . '-ar',
                ]))
            );
        }

        return $createdCarIds;
    }

    private function seedBlogs(array $carIds): void
    {
        $now = now();

        $blogs = [
            [
                'key' => 'renting-in-city',
                'title' => 'How to choose the right car for city driving',
                'description' => 'A simple checklist for selecting the ideal city rental car.',
                'content' => 'Focus on comfort, fuel efficiency, and easy parking when selecting a city car. Compare daily mileage, delivery options, and pickup convenience before booking.',
                'image' => 'https://picsum.photos/seed/home-demo-blog-1/1200/700',
                'car_indexes' => [0, 1],
            ],
            [
                'key' => 'family-trip-guide',
                'title' => 'Family trip planning with an SUV rental',
                'description' => 'What to evaluate before booking an SUV for family travel.',
                'content' => 'Check seat capacity, luggage space, and included mileage. Confirm child-seat availability and select a package that matches your trip duration and route.',
                'image' => 'https://picsum.photos/seed/home-demo-blog-2/1200/700',
                'car_indexes' => [2, 3],
            ],
            [
                'key' => 'save-on-rental',
                'title' => 'Smart ways to save on your next car rental',
                'description' => 'Practical tips to lower cost without sacrificing quality.',
                'content' => 'Book early, compare discount rates, and pick realistic mileage plans. Review fuel and insurance terms to avoid hidden charges and unexpected fees.',
                'image' => 'https://picsum.photos/seed/home-demo-blog-3/1200/700',
                'car_indexes' => [4, 5],
            ],
        ];

        foreach ($blogs as $blogIndex => $item) {
            $slug = 'home-demo-blog-' . $item['key'];
            $blogId = (int) (DB::table('blog_translations')->where('slug', $slug)->value('blog_id') ?? 0);

            $blogPayload = $this->tableData('blogs', [
                'show_in_home' => true,
                'image_path' => $item['image'],
                'description' => $item['description'],
                'slug' => $slug,
                'is_active' => true,
                'created_at' => $now->copy()->addSeconds($blogIndex),
                'updated_at' => $now->copy()->addSeconds($blogIndex),
            ]);

            if ($blogId > 0) {
                DB::table('blogs')->where('id', $blogId)->update($blogPayload);
            } else {
                $blogId = DB::table('blogs')->insertGetId($blogPayload);
            }

            $translationBase = [
                'title' => $item['title'],
                'description' => $item['description'],
                'content' => $item['content'],
                'meta_title' => $item['title'],
                'meta_description' => $item['description'],
                'meta_keywords' => $this->keywords(['car rental blog', 'rental tips', 'travel tips']),
                'robots_index' => 'index',
                'robots_follow' => 'follow',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('blog_translations')->updateOrInsert(
                ['blog_id' => $blogId, 'locale' => 'en'],
                $this->tableData('blog_translations', array_merge($translationBase, [
                    'blog_id' => $blogId,
                    'locale' => 'en',
                    'slug' => $slug,
                ]))
            );

            DB::table('blog_translations')->updateOrInsert(
                ['blog_id' => $blogId, 'locale' => 'ar'],
                $this->tableData('blog_translations', array_merge($translationBase, [
                    'blog_id' => $blogId,
                    'locale' => 'ar',
                    'slug' => $slug . '-ar',
                ]))
            );

            DB::table('blog_cars')->where('blog_id', $blogId)->delete();

            foreach ($item['car_indexes'] as $carIndex) {
                if (!isset($carIds[$carIndex])) {
                    continue;
                }

                DB::table('blog_cars')->updateOrInsert(
                    ['blog_id' => $blogId, 'car_id' => $carIds[$carIndex]],
                    $this->tableData('blog_cars', [
                        'blog_id' => $blogId,
                        'car_id' => $carIds[$carIndex],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                );
            }
        }
    }

    private function seedFaqs(): void
    {
        $now = now();

        $faqs = [
            [
                'key' => 'booking-requirements',
                'question' => 'What do I need to book a car?',
                'answer' => 'A valid driving license, identification document, and a confirmed payment method are required for booking.',
            ],
            [
                'key' => 'security-deposit',
                'question' => 'Is there a security deposit?',
                'answer' => 'Yes, a refundable security deposit may apply depending on the selected car category.',
            ],
            [
                'key' => 'delivery-options',
                'question' => 'Can you deliver the car to my location?',
                'answer' => 'Yes, delivery is available for selected cars and locations based on availability.',
            ],
            [
                'key' => 'mileage-policy',
                'question' => 'How is mileage calculated?',
                'answer' => 'Each rental plan includes mileage limits. Extra distance is charged according to the policy.',
            ],
            [
                'key' => 'late-return',
                'question' => 'What happens if I return the car late?',
                'answer' => 'Late returns may incur additional charges according to the rental agreement terms.',
            ],
            [
                'key' => 'cancellation-policy',
                'question' => 'Can I cancel my booking?',
                'answer' => 'Yes, cancellation is supported under the active cancellation terms for your booking.',
            ],
        ];

        foreach ($faqs as $index => $item) {
            $slug = 'home-demo-faq-' . $item['key'];
            $faqId = (int) (DB::table('faq_translations')->where('slug', $slug)->value('faq_id') ?? 0);

            $faqPayload = $this->tableData('faqs', [
                'show_in_home' => true,
                'is_active' => true,
                'order' => $index + 1,
                'created_at' => $now->copy()->addSeconds($index),
                'updated_at' => $now->copy()->addSeconds($index),
            ]);

            if ($faqId > 0) {
                DB::table('faqs')->where('id', $faqId)->update($faqPayload);
            } else {
                $faqId = DB::table('faqs')->insertGetId($faqPayload);
            }

            $translationBase = [
                'question' => $item['question'],
                'answer' => $item['answer'],
                'meta_title' => $item['question'],
                'meta_description' => $item['answer'],
                'meta_keywords' => $this->keywords(['faq', 'car rental', 'booking help']),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('faq_translations')->updateOrInsert(
                ['faq_id' => $faqId, 'locale' => 'en'],
                $this->tableData('faq_translations', array_merge($translationBase, [
                    'faq_id' => $faqId,
                    'locale' => 'en',
                    'slug' => $slug,
                ]))
            );

            DB::table('faq_translations')->updateOrInsert(
                ['faq_id' => $faqId, 'locale' => 'ar'],
                $this->tableData('faq_translations', array_merge($translationBase, [
                    'faq_id' => $faqId,
                    'locale' => 'ar',
                    'slug' => $slug . '-ar',
                ]))
            );
        }
    }

    private function ensureCategoryIds(int $minimum): array
    {
        $ids = Category::query()
            ->where('is_active', true)
            ->pluck('id')
            ->values()
            ->all();

        $counter = 1;
        while (count($ids) < $minimum) {
            $slug = 'home-demo-category-' . $counter;
            $categoryId = (int) (DB::table('category_translations')->where('slug', $slug)->value('category_id') ?? 0);
            $now = now();

            if ($categoryId <= 0) {
                $categoryId = DB::table('categories')->insertGetId($this->tableData('categories', [
                    'image_path' => 'images/default_category.png',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            $translationBase = [
                'name' => 'Home Demo Category ' . $counter,
                'description' => 'Category created by HomeDataSeeder for homepage demo content.',
                'meta_title' => 'Home Demo Category ' . $counter,
                'meta_description' => 'Demo category for homepage cards.',
                'meta_keywords' => $this->keywords(['category', 'home demo', 'car rental']),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('category_translations')->updateOrInsert(
                ['category_id' => $categoryId, 'locale' => 'en'],
                $this->tableData('category_translations', array_merge($translationBase, [
                    'category_id' => $categoryId,
                    'locale' => 'en',
                    'slug' => $slug,
                ]))
            );

            DB::table('category_translations')->updateOrInsert(
                ['category_id' => $categoryId, 'locale' => 'ar'],
                $this->tableData('category_translations', array_merge($translationBase, [
                    'category_id' => $categoryId,
                    'locale' => 'ar',
                    'slug' => $slug . '-ar',
                ]))
            );

            $ids[] = $categoryId;
            $counter++;
        }

        return array_slice(array_values(array_unique($ids)), 0, $minimum);
    }

    private function ensureBrandIds(int $minimum): array
    {
        $ids = Brand::query()
            ->where('is_active', true)
            ->pluck('id')
            ->values()
            ->all();

        $counter = 1;
        while (count($ids) < $minimum) {
            $slug = 'home-demo-brand-' . $counter;
            $brandId = (int) (DB::table('brand_translations')->where('slug', $slug)->value('brand_id') ?? 0);
            $now = now();

            if ($brandId <= 0) {
                $brandId = DB::table('brands')->insertGetId($this->tableData('brands', [
                    'logo_path' => 'website/assets/img/brand-logo.svg',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }

            $translationBase = [
                'name' => 'Home Demo Brand ' . $counter,
                'meta_title' => 'Home Demo Brand ' . $counter,
                'meta_description' => 'Demo brand for homepage slider.',
                'meta_keywords' => $this->keywords(['brand', 'home demo', 'car rental']),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            DB::table('brand_translations')->updateOrInsert(
                ['brand_id' => $brandId, 'locale' => 'en'],
                $this->tableData('brand_translations', array_merge($translationBase, [
                    'brand_id' => $brandId,
                    'locale' => 'en',
                    'slug' => $slug,
                ]))
            );

            DB::table('brand_translations')->updateOrInsert(
                ['brand_id' => $brandId, 'locale' => 'ar'],
                $this->tableData('brand_translations', array_merge($translationBase, [
                    'brand_id' => $brandId,
                    'locale' => 'ar',
                    'slug' => $slug . '-ar',
                ]))
            );

            $ids[] = $brandId;
            $counter++;
        }

        return array_slice(array_values(array_unique($ids)), 0, $minimum);
    }

    private function ensureColorId(): int
    {
        $existingId = (int) (Color::query()->where('is_active', true)->value('id') ?? 0);
        if ($existingId > 0) {
            return $existingId;
        }

        $slug = 'home-demo-color-black';
        $colorId = (int) (DB::table('color_translations')->where('slug', $slug)->value('color_id') ?? 0);
        $now = now();

        if ($colorId <= 0) {
            $colorId = DB::table('colors')->insertGetId($this->tableData('colors', [
                'color_code' => '#000000',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $translationBase = [
            'name' => 'Black',
            'meta_title' => 'Black Color',
            'meta_description' => 'Black exterior color option.',
            'meta_keywords' => $this->keywords(['black', 'color', 'car']),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('color_translations')->updateOrInsert(
            ['color_id' => $colorId, 'locale' => 'en'],
            $this->tableData('color_translations', array_merge($translationBase, [
                'color_id' => $colorId,
                'locale' => 'en',
                'slug' => $slug,
            ]))
        );

        DB::table('color_translations')->updateOrInsert(
            ['color_id' => $colorId, 'locale' => 'ar'],
            $this->tableData('color_translations', array_merge($translationBase, [
                'color_id' => $colorId,
                'locale' => 'ar',
                'slug' => $slug . '-ar',
            ]))
        );

        return $colorId;
    }

    private function ensureGearTypeId(): int
    {
        $existingId = (int) (Gear_type::query()->where('is_active', true)->value('id') ?? 0);
        if ($existingId > 0) {
            return $existingId;
        }

        $slug = 'home-demo-gear-automatic';
        $gearTypeId = (int) (DB::table('gear_type_translations')->where('slug', $slug)->value('gear_type_id') ?? 0);
        $now = now();

        if ($gearTypeId <= 0) {
            $gearTypeId = DB::table('gear_types')->insertGetId($this->tableData('gear_types', [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $translationBase = [
            'name' => 'Automatic',
            'meta_title' => 'Automatic Gear Type',
            'meta_description' => 'Automatic transmission option.',
            'meta_keywords' => $this->keywords(['automatic', 'gear type', 'transmission']),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('gear_type_translations')->updateOrInsert(
            ['gear_type_id' => $gearTypeId, 'locale' => 'en'],
            $this->tableData('gear_type_translations', array_merge($translationBase, [
                'gear_type_id' => $gearTypeId,
                'locale' => 'en',
                'slug' => $slug,
            ]))
        );

        DB::table('gear_type_translations')->updateOrInsert(
            ['gear_type_id' => $gearTypeId, 'locale' => 'ar'],
            $this->tableData('gear_type_translations', array_merge($translationBase, [
                'gear_type_id' => $gearTypeId,
                'locale' => 'ar',
                'slug' => $slug . '-ar',
            ]))
        );

        return $gearTypeId;
    }

    private function ensureYearId(): int
    {
        $yearValue = (string) now()->year;
        $yearId = (int) (Year::query()->where('year', $yearValue)->value('id') ?? 0);

        if ($yearId > 0) {
            return $yearId;
        }

        return (int) DB::table('years')->insertGetId($this->tableData('years', [
            'year' => $yearValue,
        ]));
    }

    private function keywords(array $values): string
    {
        $keywords = array_values(array_map(
            static fn (string $value): array => ['value' => $value],
            $values
        ));

        return (string) json_encode($keywords, JSON_UNESCAPED_UNICODE);
    }

    private function tableData(string $table, array $payload): array
    {
        $columns = $this->columnsCache[$table] ??= array_flip(Schema::getColumnListing($table));

        return array_intersect_key($payload, $columns);
    }
}
