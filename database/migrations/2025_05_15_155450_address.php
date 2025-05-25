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
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->string('recipient_name', 50);
            $table->text('full_address');
            $table->string('note')->nullable();
            $table->string('phone_number', 20);
            $table->string('province_name', 50);
            $table->string('city_name', 50);
            $table->string('district_name', 50);
            $table->string('subdistrict_name', 50);
            $table->string('zip_code', 50);
            $table->string('address_label', 100);
            $table->string('destination_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
