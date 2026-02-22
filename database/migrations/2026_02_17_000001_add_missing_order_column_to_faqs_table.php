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
        if (!Schema::hasColumn('faqs', 'order')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->unsignedInteger('order')->default(0)->after('show_in_home');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('faqs', 'order')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
