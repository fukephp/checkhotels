<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableIndexPlaceIdHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('hotels', 'place_id')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->index('place_id');
            });
        }
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
                $table->dropIndex('hotels_place_id_index');
            });
        }
    }
}
