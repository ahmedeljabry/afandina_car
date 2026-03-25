<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends GenericController
{
    public function __construct()
    {
        parent::__construct('category');
        $this->slugField = 'name';
        $this->translatableFields = ['name', 'title', 'description', 'article'];
        $this->nonTranslatableFields = ['is_active'];
        $this->uploadedfiles = ['image_path'];
    }

    public function index(): View
    {
        $localeCodes = $this->data['activeLanguages']->pluck('code')->all();

        $this->data['items'] = Category::query()
            ->with([
                'translations' => function ($query) use ($localeCodes) {
                    $query->whereIn('locale', $localeCodes);
                },
            ])
            ->withCount([
                'cars',
                'cars as active_cars_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->latest()
            ->paginate(9);

        $this->data['stats'] = [
            'total' => Category::query()->count(),
            'active' => Category::query()->where('is_active', true)->count(),
            'inactive' => Category::query()->where('is_active', false)->count(),
            'with_image' => Category::query()
                ->whereNotNull('image_path')
                ->where('image_path', '!=', '')
                ->count(),
        ];

        return view('pages.admin.categories.index', $this->data);
    }

    public function create(): View
    {
        $this->data['formMode'] = 'create';

        return view('pages.admin.categories.create', $this->data);
    }

    public function edit($id): View
    {
        $localeCodes = $this->data['activeLanguages']->pluck('code')->all();

        $this->data['item'] = Category::query()
            ->with([
                'translations' => function ($query) use ($localeCodes) {
                    $query->whereIn('locale', $localeCodes);
                },
                'seoQuestions' => function ($query) use ($localeCodes) {
                    $query->whereIn('locale', $localeCodes);
                },
            ])
            ->withCount([
                'cars',
                'cars as active_cars_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->findOrFail($id);

        $this->data['formMode'] = 'edit';

        return view('pages.admin.categories.edit', $this->data);
    }

    public function show($id): View
    {
        $localeCodes = $this->data['activeLanguages']->pluck('code')->all();

        $this->data['item'] = Category::query()
            ->with([
                'translations' => function ($query) use ($localeCodes) {
                    $query->whereIn('locale', $localeCodes);
                },
                'seoQuestions' => function ($query) use ($localeCodes) {
                    $query->whereIn('locale', $localeCodes);
                },
            ])
            ->withCount([
                'cars',
                'cars as active_cars_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->findOrFail($id);

        return view('pages.admin.categories.show', $this->data);
    }

    public function store(Request $request)
    {
        $this->prepareRequest($request);
        $validatedData = $request->validate($this->rules());

        DB::beginTransaction();

        try {
            $row = $this->model::create([
                'is_active' => $validatedData['is_active'] ?? false,
            ]);

            $this->handleModelTranslations($validatedData, $row);
            $this->storeCategoryImage($request, $row);
            $this->handleStoreSEOQuestions($validatedData, $row);

            DB::commit();

            return redirect()
                ->route('admin.' . $this->modelName . '.index')
                ->with('success', ucfirst($this->modelName) . ' created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error occurred while creating ' . $this->modelName . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $this->prepareRequest($request);
        $validatedData = $request->validate($this->rules((int) $id, true));

        DB::beginTransaction();

        try {
            $row = $this->model::findOrFail($id);

            if ($request->hasFile('image_path') && $row->image_path) {
                Storage::disk('public')->delete($row->image_path);
            }

            $row->is_active = $validatedData['is_active'] ?? false;
            $row->save();

            $this->handleModelTranslations($validatedData, $row, (int) $id);
            $this->storeCategoryImage($request, $row);
            $this->handleUpdateSEOQuestions($validatedData, $row);

            DB::commit();

            return redirect()
                ->route('admin.' . $this->modelName . '.index')
                ->with('success', ucfirst($this->modelName) . ' updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error occurred while updating ' . $this->modelName . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function rules(?int $ignoreId = null, bool $isUpdate = false): array
    {
        return [
            'name.*' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($ignoreId) {
                    preg_match('/name\.(\w+)/', $attribute, $matches);
                    $locale = $matches[1] ?? null;

                    if (!$locale) {
                        return;
                    }

                    $query = CategoryTranslation::query()
                        ->where('name', $value)
                        ->where('locale', $locale);

                    if ($ignoreId) {
                        $query->where('category_id', '!=', $ignoreId);
                    }

                    if ($query->exists()) {
                        $fail("The name '{$value}' has already been taken for the locale '{$locale}'.");
                    }
                },
            ],
            'image_path' => ($isUpdate ? 'nullable' : 'required') . '|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
            'description.*' => 'required|string',
            'article.*' => 'nullable|string',
            'title.*' => 'nullable|string|max:255',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'robots_index.*' => 'nullable',
            'robots_follow.*' => 'nullable',
            'is_active' => 'boolean',
        ];
    }

    protected function prepareRequest(Request $request): void
    {
        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'meta_keywords' => $this->normalizeMetaKeywordsPayload((array) $request->input('meta_keywords', [])),
        ]);
    }

    protected function normalizeMetaKeywordsPayload(array $metaKeywords): array
    {
        $normalized = [];

        foreach ($metaKeywords as $locale => $value) {
            $normalized[$locale] = $this->serializeKeywordList($value);
        }

        return $normalized;
    }

    protected function serializeKeywordList($value): ?string
    {
        if (is_array($value)) {
            $keywords = collect($value)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return trim((string) ($item['value'] ?? ''));
                    }

                    return trim((string) $item);
                })
                ->filter()
                ->unique()
                ->values();

            return $keywords->isEmpty()
                ? null
                : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $keywords = collect($decoded)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return trim((string) ($item['value'] ?? ''));
                    }

                    return trim((string) $item);
                })
                ->filter()
                ->unique()
                ->values();

            return $keywords->isEmpty()
                ? null
                : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
        }

        $keywords = collect(preg_split('/[\r\n,]+/', $value) ?: [])
            ->map(fn($keyword) => trim((string) $keyword))
            ->filter()
            ->unique()
            ->values();

        return $keywords->isEmpty()
            ? null
            : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
    }

    protected function storeCategoryImage(Request $request, Category $category): void
    {
        if (!$request->hasFile('image_path')) {
            return;
        }

        $category->image_path = $request->file('image_path')->store('categories', 'public');
        $category->save();
    }
}
