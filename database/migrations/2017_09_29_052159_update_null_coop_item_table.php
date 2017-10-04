<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullCoopItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coop_items', function (Blueprint $table) {
            $table->string('item_name')->nullable()->change();
            $table->smallInteger('item_quantity', false, true)->nullable()->change();
            $table->string('item_uom', 30)->nullable()->change();
            $table->decimal('item_total_amount', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coop_items', function (Blueprint $table) {
            $table->string('item_name')->change();
            $table->smallInteger('item_quantity', false, true)->change();
            $table->string('item_uom', 30)->change();
            $table->decimal('item_total_amount', 15, 2)->change();
        });
    }
}
