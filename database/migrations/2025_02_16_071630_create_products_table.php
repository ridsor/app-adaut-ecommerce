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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string("name");
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string("image");
            $table->unsignedBigInteger("price");
            $table->text("description");
            $table->integer('stock')->default(0);
            $table->integer('weight');
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};