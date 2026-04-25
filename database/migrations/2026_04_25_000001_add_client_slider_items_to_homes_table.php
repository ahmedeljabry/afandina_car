<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (!Schema::hasColumn('homes', 'client_slider_items')) {
                $table->longText('client_slider_items')->nullable()->after('rental_section_grid_image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'client_slider_items')) {
                $table->dropColumn('client_slider_items');
            }
        });
    }
};
