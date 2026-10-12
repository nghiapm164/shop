<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm các thuộc tính phục vụ chatbot AI tư vấn sản phẩm.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('gender', 20)->nullable()->after('brand_id');   // nam | nu | unisex
            $table->string('sport', 30)->nullable()->after('gender');      // bong-da | chay-bo | gym ...
            $table->string('purpose', 30)->nullable()->after('sport');     // tap-luyen | thi-dau | hang-ngay
            $table->string('style', 30)->nullable()->after('purpose');     // slim | regular | athletic | relaxed

            $table->index('gender');
            $table->index('sport');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['gender']);
            $table->dropIndex(['sport']);
            $table->dropColumn(['gender', 'sport', 'purpose', 'style']);
        });
    }
};
