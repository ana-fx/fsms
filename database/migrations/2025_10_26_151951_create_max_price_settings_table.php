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
        Schema::create('max_price_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');
            $table->decimal('max_price', 12, 2);
            $table->string('unit')->default('kg');
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Ensure one setting per category
            $table->unique('food_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('max_price_settings');
    }
};
