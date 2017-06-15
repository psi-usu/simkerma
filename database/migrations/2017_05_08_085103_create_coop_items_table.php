<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coop_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cooperation_id', false, true);
            $table->tinyInteger('item', false, true);
            $table->string('item_name');
            $table->smallInteger('item_quantity', false, true);
            $table->string('item_uom', 30);
            $table->decimal('item_total_amount', 15, 2);
            $table->string('item_annotation', 255)->nullable();
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
        Schema::dropIfExists('coop_items');
    }
}
