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
//            $table->foreignId('to_port_id')->references('id')->on('port_cities')->onDelete('cascade');
            $table->string('zip_code')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('payed', 10, 2)->nullable();
            // Subdealer id
            $table->bigInteger('team_id')->index()->nullable();
            $table->string('vehicle_owner_name')->nullable();
            $table->string('owner_id_number')->nullable();
            $table->string('owner_phone_number')->nullable();
            $table->string('container_number')->nullable();
            $table->json('images')->nullable(); // Storing image paths as JSON
            $table->string('invoice_file')->nullable(); // Path to invoice file
            $table->enum('title_received', ['no', 'yes']);
            $table->enum('key', ['no', 'yes']);
            $table->string('record_color')->nullable();
            $table->foreignId('car_status_id')->constrained()->cascadeOnDelete();
            $table->text('comment')->nullable();
            $table->boolean('is_deleted')->default(0); // 0 for not deleted, 1 for deleted
            $table->json('balance_accounting')->nullable(); // Store balance accounting as JSON
            $table->string('warehouse')->nullable();
            $table->string('internal_shipping')->nullable(); // Cost
            $table->string('company_name')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('pickup_dates')->nullable();
            $table->string('storage')->nullable();
            $table->string('storage_cost')->nullable();
            $table->string('title_delivery')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_address')->nullable();
            $table->string('payment_photo')->nullable();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('dispatch_id')->nullable();
            $table->integer('order_column')->index()->nullable();
            $table->foreignId('container_status_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('container_status')->default(1);
            $table->timestamp('arrival_time')->nullable();
            $table->string('title_take')->nullable();
            $table->string('remark')->nullable();
            $table->string('bill_of_loading')->nullable();
            $table->tinyText('container_images')->nullable();
            $table->string('balance')->nullable();
            $table->string('left_balance')->nullable();
            $table->string('payment_company')->nullable();
            $table->string('total_cost')->nullable(); // Total amount of the car inlcuding all costs before column name was debit
            $table->decimal('amount_due', 10, 2)->nullable();
            $table->string('extra_price')->nullable();
            $table->dateTime('last_amount_update_date')->nullable();
            $table->enum('is_dispatch', ['no', 'yes'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
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
