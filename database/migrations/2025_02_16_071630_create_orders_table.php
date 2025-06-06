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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('note')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['unpaid', 'packed', 'submitted', 'completed', 'failed'])->default('unpaid');
            $table->timestamps();

            $table->fullText(['order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
