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
        Schema::table('articles', function (Blueprint $table) {
            $table->unique('url')->name('articles_url_unique');
            $table->index('category')->name('articles_category_index');
            $table->index('source')->name('articles_source_index');
            $table->index('published_at')->name('articles_published_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropUnique('articles_url_unique');
            $table->dropIndex('articles_category_index');
            $table->dropIndex('articles_source_index');
            $table->dropIndex('articles_published_at_index');
        });
    }
};
