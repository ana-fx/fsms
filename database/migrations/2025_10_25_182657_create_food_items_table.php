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
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('sub_name')->nullable(); // Optional sub-name from supplier (e.g., "Merah", "Premium", "2 KARUNG")
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('max_price', 12, 2)->nullable();
            $table->decimal('default_price_increment', 12, 2)->default(500);
            $table->enum('price_increment_type', ['fixed', 'percentage'])->default('fixed');
            $table->string('unit'); // kg, liter, pcs, karton, dus, dll
            $table->integer('stock')->default(0);
            $table->integer('min_purchase')->default(0);
            $table->integer('max_purchase')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
