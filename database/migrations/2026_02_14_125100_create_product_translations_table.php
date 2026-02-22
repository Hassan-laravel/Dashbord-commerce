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
Schema::create('product_translations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->string('locale')->index(); // ar, en, nl...

        $table->string('name');
        $table->string('slug');
        $table->text('short_description')->nullable();
        $table->longText('description')->nullable();


        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();

        $table->unique(['product_id', 'locale']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
