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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('number_of_cars')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(0);
            $table->string('total_balance')->default(0);
            $table->string('left_balance')->default(0);
            $table->unsignedBigInteger('child_of')->nullable();
            $table->string('personal_number')->default(0);
            $table->string('extra_for_team')->default(0);
            $table->string('username')->nullable();
            $table->string('unhashed_password')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
