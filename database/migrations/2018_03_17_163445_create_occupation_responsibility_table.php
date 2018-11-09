<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccupationResponsibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupation_responsibility', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occupation_id');
            $table->unsignedInteger('responsibility_id');

            $table->foreign('occupation_id')
                ->references('id')
                ->on('occupations')
                ->onDelete('cascade');

            $table->foreign('responsibility_id')
                ->references('id')
                ->on('responsibilities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('occupation_responsibility', function (Blueprint $table) {
            $table->dropForeign('occupation_responsibility_occupation_id_foreign');
            $table->dropForeign('occupation_responsibility_responsibility_id_foreign');
        });

        Schema::dropIfExists('occupation_responsibility');
    }
}
