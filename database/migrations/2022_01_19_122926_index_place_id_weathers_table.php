<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexPlaceIdWeathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('weathers', 'place_id')) {
            Schema::table('weathers', function (Blueprint $table) {
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
        if (Schema::hasColumn('weathers', 'place_id')) {
            Schema::table('weathers', function (Blueprint $table) {
                $table->dropIndex('weathers_place_id_index');
            });
        }
    }
}
