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
            $table->string('recipient_name')->nullable()->after('notes');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->text('delivery_address')->nullable()->after('recipient_phone');
            $table->string('city')->nullable()->after('delivery_address');
            $table->string('postal_code')->nullable()->after('city');
            $table->text('delivery_notes')->nullable()->after('postal_code');
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
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'delivery_address',
                'city',
                'postal_code',
                'delivery_notes',
            ]);
        });

        // Drop user_delivery_addresses table
        Schema::dropIfExists('user_delivery_addresses');
    }
};
