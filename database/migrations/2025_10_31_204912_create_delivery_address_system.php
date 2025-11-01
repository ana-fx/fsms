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
        // Add delivery address columns to food_requests table
        Schema::table('food_requests', function (Blueprint $table) {
            $table->foreignId('food_item_id')->nullable()->after('food_category_id')->constrained('food_items')->onDelete('cascade');
            $table->string('order_number')->unique()->after('id');
            $table->string('recipient_name')->nullable()->after('notes');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->text('delivery_address')->nullable()->after('recipient_phone');
            $table->string('city')->nullable()->after('delivery_address');
            $table->string('postal_code')->nullable()->after('city');
            $table->text('delivery_notes')->nullable()->after('postal_code');
            $table->string('payment_proof')->nullable()->after('delivery_notes');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
        });

        // Create user_delivery_addresses table
        Schema::create('user_delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->nullable(); // Home, Office, etc.
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('delivery_address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop delivery address columns from food_requests
        Schema::table('food_requests', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
            $table->dropColumn([
                'food_item_id',
                'order_number',
                'recipient_name',
                'recipient_phone',
                'delivery_address',
                'city',
                'postal_code',
                'delivery_notes',
                'payment_proof',
                'payment_proof_uploaded_at',
            ]);
        });

        // Drop user_delivery_addresses table
        Schema::dropIfExists('user_delivery_addresses');
    }
};
