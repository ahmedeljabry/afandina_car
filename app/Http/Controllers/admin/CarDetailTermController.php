<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarDetailTermController extends Controller
{
    private const TERM_FIELDS = [
        'mileage_policy' => 'Mileage Policy',
        'fuel_policy' => 'Fuel Policy',
        'deposit_policy' => 'Deposit Policy',
        'rental_policy' => 'Rental Policy',
    ];

    private const PRIORITY_LOCALES = ['en', 'ar', 'ru'];

    public function edit(): View
    {
        $home = $this->resolveHomePage();
        $languages = $this->editorLanguages();
        $translationsByLocale = $home->translations->keyBy('locale');

        return view('pages.admin.car_detail_terms.edit', [
            'item' => $home,
            'activeLanguages' => $languages,
            'translationsByLocale' => $translationsByLocale,
            'fieldLabels' => self::TERM_FIELDS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mileage_policy.*' => 'nullable|string',
            'fuel_policy.*' => 'nullable|string',
            'deposit_policy.*' => 'nullable|string',
            'rental_policy.*' => 'nullable|string',
        ]);

        $home = $this->resolveHomePage();
        $languages = $this->editorLanguages();

        DB::transaction(function () use ($home, $languages, $validated): void {
            foreach ($languages as $language) {
                $locale = $language->code;
                $translationData = ['locale' => $locale];

                foreach (array_keys(self::TERM_FIELDS) as $field) {
                    if (array_key_exists($locale, $validated[$field] ?? [])) {
                        $translationData[$field] = $validated[$field][$locale];
                    }
                }

                $home->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $translationData
                );
            }
        });

        return back()->with('success', 'Car detail rental terms updated successfully.');
    }

    private function resolveHomePage(): Home
    {
        $home = Home::query()
            ->where('page_name', 'home')
            ->latest('id')
            ->first();

        if (!$home) {
            $home = Home::query()->create([
                'page_name' => 'home',
                'is_active' => true,
                'hero_type' => 'image',
            ]);
        }

        return $home->load('translations');
    }

    private function editorLanguages()
    {
        $activeLanguages = Language::query()
            ->active()
            ->get()
            ->keyBy('code');

        $orderedLocales = collect(self::PRIORITY_LOCALES)
            ->map(function (string $code) use ($activeLanguages): Language {
                return $activeLanguages->get($code) ?: new Language([
                    'code' => $code,
                    'name' => $this->fallbackLanguageName($code),
                    'is_active' => true,
                ]);
            });

        $additionalLocales = $activeLanguages
            ->reject(fn (Language $language, string $code): bool => in_array($code, self::PRIORITY_LOCALES, true))
            ->sortBy('id')
            ->values();

        return $orderedLocales
            ->concat($additionalLocales)
            ->values();
    }

    private function fallbackLanguageName(string $code): string
    {
        return [
            'en' => 'English',
            'ar' => 'Arabic',
            'ru' => 'Russian',
        ][$code] ?? strtoupper($code);
    }
}
