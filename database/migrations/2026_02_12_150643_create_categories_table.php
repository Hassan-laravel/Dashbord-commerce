<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable(); // تمت إضافة الصورة هنا
            $table->boolean('status')->default(true);
            $table->timestamps();
        });


        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('name');
            $table->string('slug')->nullable()->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['category_id', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
    }
};
