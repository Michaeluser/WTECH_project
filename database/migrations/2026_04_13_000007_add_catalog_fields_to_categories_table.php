<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('catalog_mode', 20)->default('landing')->after('description');
            $table->string('catalog_slider_caption')->nullable()->after('catalog_mode');
            $table->string('catalog_advice_title')->nullable()->after('catalog_slider_caption');
            $table->string('catalog_advice_subtitle')->nullable()->after('catalog_advice_title');
            $table->text('catalog_advice_text')->nullable()->after('catalog_advice_subtitle');
            $table->unsignedSmallInteger('nav_order')->default(0)->after('catalog_advice_text');
        });

        DB::table('categories')
            ->where('slug', 'tvaudio')
            ->update(['slug' => 'tv-audio']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'catalog_mode',
                'catalog_slider_caption',
                'catalog_advice_title',
                'catalog_advice_subtitle',
                'catalog_advice_text',
                'nav_order',
            ]);
        });

        DB::table('categories')
            ->where('slug', 'tv-audio')
            ->update(['slug' => 'tvaudio']);
    }
};
