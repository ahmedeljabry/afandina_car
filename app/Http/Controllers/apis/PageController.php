<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Get page content by slug.
     */
    public function show(Request $request, $slug)
    {
        $language = app()->getLocale() ?? $request->header('Accept-Language', 'en');
        
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->with(['translations' => function ($query) use ($language) {
                $query->where('locale', $language);
            }])
            ->firstOrFail();

        return response()->json([
            'data' => new PageResource($page),
            'status' => 'success'
        ]);
    }
}

