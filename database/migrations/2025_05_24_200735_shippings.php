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
            $table->string('name', 50);
            $table->string('code', 50);
            $table->string('description', 100);
            $table->bigInteger('cost');
            $table->string('etd', 20)->nullable();

            $table->string('recipient_name', 50);
            $table->text('address');
            $table->string('note')->nullable();
            $table->string('phone_number', 20);
            $table->string('province_name', 50);
            $table->string('city_name', 50);
            $table->string('district_name', 50);
            $table->string('subdistrict_name', 50);
            $table->string('zip_code', 50);
            $table->string('address_label', 100);
            $table->string('destination_id');

            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
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
