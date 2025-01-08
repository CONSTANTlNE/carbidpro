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
        Schema::create('customer_balances', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->nullable();
            $table->date('balance_fill_date')->nullable();
            $table->date('carpayment_date')->nullable();
            $table->string('full_name')->nullable();
            $table->date('date')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('car_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type')->index();
            $table->string('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
