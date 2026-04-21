<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (!Schema::hasColumn('homes', 'rental_section_image_path')) {
                $table->text('rental_section_image_path')->nullable()->after('hero_type');
            }

            if (!Schema::hasColumn('homes', 'rental_section_grid_image_path')) {
                $table->text('rental_section_grid_image_path')->nullable()->after('rental_section_image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'rental_section_grid_image_path')) {
                $table->dropColumn('rental_section_grid_image_path');
            }

            if (Schema::hasColumn('homes', 'rental_section_image_path')) {
                $table->dropColumn('rental_section_image_path');
            }
        });
    }
};
