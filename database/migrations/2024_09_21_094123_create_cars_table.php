<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make_model_year')->nullable();
            $table->string('lot')->nullable();
            $table->string('vin')->unique(); // Assuming VIN is unique
            $table->string('percent')->nullable();
            $table->string('type_of_fuel')->nullable();
            $table->string('title')->nullable();
            $table->string('gate_or_member')->nullable();
            $table->string('auction')->nullable();
            $table->string('load_type')->nullable();
            $table->string('from_state')->nullable();
            $table->unsignedBigInteger('to_port_id')->nullable(); // Assuming this refers to a foreign key
            $table->decimal('sub_total', 10, 2);
            $table->decimal('payed', 10, 2);
            $table->decimal('amount_due', 10, 2);
            $table->string('vehicle_owner_name')->nullable();
            $table->string('owner_id_number')->nullable();
            $table->string('owner_phone_number')->nullable();
            $table->string('container_number')->nullable();
            $table->json('images')->nullable(); // Storing image paths as JSON
            $table->string('invoice_file')->nullable(); // Path to invoice file
            $table->enum('title_received', ['no', 'yes']);
            $table->enum('key', ['no', 'yes']);
            $table->string('record_color')->nullable();
            $table->boolean('status')->default(1); // 0 for inactive, 1 for active
            $table->text('comment')->nullable();
            $table->boolean('is_deleted')->default(0); // 0 for not deleted, 1 for deleted
            $table->json('balance_accounting')->nullable(); // Store balance accounting as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
