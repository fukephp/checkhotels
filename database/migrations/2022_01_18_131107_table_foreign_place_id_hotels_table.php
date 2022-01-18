<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableForeignPlaceIdHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'place_id')) {
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
        if (Schema::hasColumn('hotels', 'place_id')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropForeign('hotels_place_id_foreign');
            });
        }
    }
}
