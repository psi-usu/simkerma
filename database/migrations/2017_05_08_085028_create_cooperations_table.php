<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCooperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_id', false, true)->nullable();
            $table->integer('cooperation_id', false, true)->nullable();
            $table->boolean('is_addendum', 1)->default(false)->nullable(); // MOU / MOA / ADDENDUM
            $table->string('coop_type', 10)->nullable(); // MOU / MOA
            $table->text('area_of_coop')->nullable();
            $table->date('sign_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('form_of_coop')->nullable(); // Dalam Negeri / Luar Negeri
            $table->string('usu_doc_no', 50)->nullable();
            $table->string('partner_doc_no', 50)->nullable();
            $table->string('file_name_ori')->nullable();
            $table->string('file_name')->nullable();
            $table->text('implementation')->nullable();
            $table->string('unit', 30)->nullable();
            $table->text('contract_amount')->nullable();
            $table->string('status', 50)->nullable();

            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coorperations');
    }
}
