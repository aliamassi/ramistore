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
        Schema::create('customization_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Regular Bread", "Kaak Bread"
            $table->string('detail')->nullable(); // e.g., "Potato", "Eggplant"
            $table->integer('orders_count')->default(0); // e.g., 11000 for "11k+ orders"
            $table->decimal('price_modifier', 8, 2)->default(0); // Additional cost
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customization_options');
    }
};
