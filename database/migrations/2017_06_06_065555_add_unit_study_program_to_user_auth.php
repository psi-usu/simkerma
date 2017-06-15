<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitStudyProgramToUserAuth extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_auths', function (Blueprint $table)
        {
            $table->string('unit', 30)->nullable();
            $table->string('sub_unit', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_auths', function (Blueprint $table)
        {
            $table->dropColumn('unit');
            $table->dropColumn('sub_unit');
        });
    }
}
