<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weathers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('api_weather_id');
            $table->string('main')->nullable();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('temp_day', 5,2)->nullable();
            $table->decimal('temp_min', 5,2)->nullable();
            $table->decimal('temp_max', 5,2)->nullable();
            $table->decimal('temp_night', 5,2)->nullable();
            $table->decimal('temp_eve', 5,2)->nullable()->nullable();
            $table->decimal('temp_morn', 5,2)->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weathers');
    }
}
