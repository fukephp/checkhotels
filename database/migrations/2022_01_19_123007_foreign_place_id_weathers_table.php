<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignPlaceIdWeathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weathers', function (Blueprint $table) {
            if (Schema::hasColumn('weathers', 'place_id')) {
                $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('weathers', 'place_id')) {
            Schema::table('weathers', function (Blueprint $table) {
                $table->dropForeign('weathers_place_id_foreign');
            });
        }
    }
}
