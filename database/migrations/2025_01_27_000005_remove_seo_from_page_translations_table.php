<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            // Remove SEO fields if they exist
            if (Schema::hasColumn('page_translations', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('page_translations', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('page_translations', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->after('only_on_us_description');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });
    }
};



