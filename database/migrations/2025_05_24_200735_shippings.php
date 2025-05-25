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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('code', 50);
            $table->string('description', 100);
            $table->bigInteger('cost');
            $table->string('etd', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
