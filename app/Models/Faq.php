<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Schema;

class Faq extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static ?bool $hasOrderColumn = null;

    protected static function boot()
    {
        parent::boot();

        // Default sorting by order when column exists; otherwise fallback by newest first.
        static::addGlobalScope('order', function ($builder) {
            $table = $builder->getModel()->getTable();

            if (static::hasOrderColumn()) {
                $builder->orderBy($table . '.order', 'asc');
                return;
            }

            $builder->orderByDesc($table . '.id');
        });
    }

    protected static function hasOrderColumn(): bool
    {
        if (static::$hasOrderColumn !== null) {
            return static::$hasOrderColumn;
        }

        try {
            static::$hasOrderColumn = Schema::hasColumn((new static)->getTable(), 'order');
        } catch (\Throwable $exception) {
            static::$hasOrderColumn = false;
        }

        return static::$hasOrderColumn;
    }

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }
}
