<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResumeTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resume_id');
            $table->string('key');
            $table->timestamps();

            $table->foreign('resume_id')
                ->references('id')
                ->on('resumes')
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
        Schema::table('resume_tokens', function (Blueprint $table) {
            $table->dropForeign('resume_tokens_resume_id_foreign');
        });

        Schema::dropIfExists('resume_tokens');
    }
}
