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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('link')->nullable();
            $table->enum('position', ['home_top', 'home_middle', 'home_bottom', 'category', 'product'])->default('home_top');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
