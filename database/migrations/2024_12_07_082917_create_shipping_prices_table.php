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
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->id();
            $table->string('price');
            $table->json('auction_ids');
            $table->unsignedBigInteger('from_location_id');
            $table->unsignedBigInteger('to_port_id');
            $table->foreign('from_location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('to_port_id')->references('id')->on('locations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
