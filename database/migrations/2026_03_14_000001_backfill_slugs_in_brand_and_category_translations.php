<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill brand_translations
        $brands = DB::table('brand_translations')
            ->where('locale', 'en')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get(['id', 'brand_id', 'name']);

        foreach ($brands as $row) {
            $baseSlug = Str::slug($row->name ?: 'brand-' . $row->brand_id);
            $slug = $baseSlug;
            $counter = 1;

            while (DB::table('brand_translations')
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            DB::table('brand_translations')
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        }

        // Backfill category_translations
        $categories = DB::table('category_translations')
            ->where('locale', 'en')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get(['id', 'category_id', 'name']);

        foreach ($categories as $row) {
            $baseSlug = Str::slug($row->name ?: 'category-' . $row->category_id);
            $slug = $baseSlug;
            $counter = 1;

            while (DB::table('category_translations')
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            DB::table('category_translations')
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Cannot safely reverse a backfill
    }
};
