<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->index();
            $table->string('label');
            $table->text('value');
            $table->timestamps();
        });
    }
};
