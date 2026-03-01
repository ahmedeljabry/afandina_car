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
        Schema::table('templates', function (Blueprint $table) {
            $table->string('site_name')->nullable()->after('logo_path');
            $table->string('dark_logo_path')->nullable()->after('site_name');
            $table->string('favicon_path')->nullable()->after('dark_logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['site_name', 'dark_logo_path', 'favicon_path']);
        });
    }
};
