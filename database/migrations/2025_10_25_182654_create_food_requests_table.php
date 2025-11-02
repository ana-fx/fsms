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
        Schema::create('food_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('food_category_id')->constrained('food_categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // kg, liter, pcs, etc
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'payment_pending', 'paid', 'shipping', 'delivered', 'completed', 'rejected'])->default('pending');
            $table->date('requested_date');
            $table->date('needed_date');
            $table->foreignId('shipped_by')->nullable()->after('needed_date')->constrained('users')->onDelete('set null')->comment('Supplier yang melakukan pengiriman');
            $table->timestamp('shipped_at')->nullable();
            $table->string('delivery_photo')->nullable()->comment('Foto bukti barang sudah diterima');
            $table->timestamp('delivery_photo_uploaded_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_requests');
    }
};
