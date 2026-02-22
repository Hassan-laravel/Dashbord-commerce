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
Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_email')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_phone')->nullable(); // أضفت الهاتف بناءً على طلبك السابق
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });

        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained('settings')->cascadeOnDelete();
            $table->string('locale')->index();

            $table->string('site_name')->nullable();
            $table->text('site_description')->nullable();
            $table->string('copyright')->nullable();

            $table->unique(['setting_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_translations');
        Schema::dropIfExists('settings');
    }
};
