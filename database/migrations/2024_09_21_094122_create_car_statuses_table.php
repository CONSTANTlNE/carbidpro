<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('car_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Insert default values
//        DB::table('car_statuses')->insert([
//            ['slug' => 'for-dispatch', 'name' => 'For Dispatch'],
//            ['slug' => 'listed', 'name' => 'Listed'],
//            ['slug' => 'assign', 'name' => 'Assign'],
//            ['slug' => 'pick-up', 'name' => 'Pick UP'],
//            ['slug' => 'delivered', 'name' => 'Delivered'],
//            ['slug' => 'payment', 'name' => 'Payment'],
//            ['slug' => 'dispatched', 'name' => 'Dispatched'],
//        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_statuses');
    }
};
