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
        Schema::create('container_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->timestamps();
            $table->string('photo')->nullable();
            $table->string('cost')->nullable();
            $table->string('container_id')->nullable();
            $table->string('booking_id')->nullable();
            $table->bigInteger('to_port_id')->nullable();
            $table->boolean('is_email_sent')->default(false);
            $table->timestamp('arrival_time')->nullable();
            $table->string('invoice_file')->nullable();
            $table->string('thc_invoice')->nullable();
            $table->string('title_in_office')->nullable();
            $table->string('trt_payed')->nullable();
            $table->string('thc_payed')->nullable();
            $table->string('is_green')->nullable();
            $table->string('terminal')->nullable();
            $table->string('cont_status')->nullable();
            $table->string('est_open_date')->nullable();
            $table->string('opened')->nullable();
            $table->string('open_payed')->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('email_sent_date')->nullable();
            $table->string('payment_file_2')->nullable();
            $table->string('payment_file_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_groups');
    }
};
