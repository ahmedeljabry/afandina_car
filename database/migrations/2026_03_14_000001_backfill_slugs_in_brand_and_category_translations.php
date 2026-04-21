<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('brand_translations', 'slug')) {
            $this->backfillSlugs('brand_translations', 'brand_id', 'brand');
        }

        if (Schema::hasColumn('category_translations', 'slug')) {
            $this->backfillSlugs('category_translations', 'category_id', 'category');
        }
    }

    private function backfillSlugs(string $table, string $foreignKey, string $fallbackPrefix): void
    {
        $rows = DB::table($table)
            ->where('locale', 'en')
            ->where(function ($query): void {
                $query->whereNull('slug')
                    ->orWhere('slug', '');
            })
            ->get(['id', $foreignKey, 'name']);

        foreach ($rows as $row) {
            $baseSlug = Str::slug($row->name ?: $fallbackPrefix . '-' . $row->{$foreignKey});
            $slug = $baseSlug;
            $counter = 1;

            while (DB::table($table)
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            DB::table($table)
                ->where('id', $row->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Cannot safely reverse a backfill
    }
};
