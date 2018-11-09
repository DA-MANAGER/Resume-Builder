<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribeResumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_resume', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resume_id');
            $table->unsignedInteger('subscription_id');

            $table->timestamps();

            $table->foreign('resume_id')
                ->references('id')
                ->on('resumes')
                ->onDelete('cascade');

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
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
        Schema::table('subscribe_resume', function (Blueprint $table) {
            $table->dropForeign('subscribe_resume_resume_id_foreign');
            $table->dropForeign('subscribe_resume_subscription_id_foreign');
        });

        Schema::dropIfExists('subscribe_resume');
    }
}
