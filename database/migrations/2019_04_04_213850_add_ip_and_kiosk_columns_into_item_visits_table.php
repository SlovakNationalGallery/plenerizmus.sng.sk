<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpAndKioskColumnsIntoItemVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_visits', function($table)
        {
            $table->string('ip')->nullable();
            $table->boolean('is_kiosk')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_visits', function($table)
        {
            $table->dropColumn('ip');
            $table->dropColumn('is_kiosk');
        });
    }
}
