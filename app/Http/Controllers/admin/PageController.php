<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index()
    {
        $pages = Page::with('translations')->paginate(10);
        $activeLanguages = Language::active()->get();
        
        return view('pages.admin.pages.index', compact('pages', 'activeLanguages'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit($slug)
    {
        $page = Page::where('slug', $slug)->with('translations')->firstOrFail();
        $activeLanguages = Language::active()->get();
        
        return view('pages.admin.pages.edit', compact('page', 'activeLanguages'));
    }

    /**
     * Update the specified page.
     */
    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $activeLanguages = Language::active()->get();

        // Validation rules
        $rules = [
            'is_active' => 'boolean',
        ];

        // Add validation for each language
        foreach ($activeLanguages as $lang) {
            $rules['title.' . $lang->code] = 'nullable|string|max:255';
            $rules['description.' . $lang->code] = 'nullable|string';
            $rules['sub_description.' . $lang->code] = 'nullable|string';
            // SEO fields
            $rules['meta_title.' . $lang->code] = 'nullable|string|max:255';
            $rules['meta_description.' . $lang->code] = 'nullable|string';
            $rules['meta_keywords.' . $lang->code] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update page active status
            $page->is_active = $request->has('is_active') ? true : false;
            $page->save();

            // Update or create translations
            foreach ($activeLanguages as $lang) {
                $translation = $page->translations()->where('locale', $lang->code)->first();
                
                $translationData = [
                    'locale' => $lang->code,
                    'title' => $request->input('title.' . $lang->code),
                    'description' => $request->input('description.' . $lang->code),
                    'sub_description' => $request->input('sub_description.' . $lang->code),
                    // SEO fields
                    'meta_title' => $request->input('meta_title.' . $lang->code),
                    'meta_description' => $request->input('meta_description.' . $lang->code),
                    'meta_keywords' => $request->input('meta_keywords.' . $lang->code),
                ];

                if ($translation) {
                    $translation->update($translationData);
                } else {
                    $page->translations()->create($translationData);
                }
            }

            DB::commit();
            return redirect()->route('admin.pages.edit', $page->slug)
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error updating page: ' . $e->getMessage())
                ->withInput();
        }
    }
}

