<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('user_id');
            $table->string('subject')->nullable();
            $table->json('recipients')->nullable();
            $table->string('email_signature')->nullable()->default('user');
            $table->boolean('schedule_email_to_send')->nullable()->default(0);
            $table->boolean('set_send_date')->nullable()->default(0);
            $table->date('send_date')->nullable();
            $table->string('repeat')->nullable();
            $table->string('repeats_every_item')->nullable();
            $table->string('repeats_every_type')->nullable();
            $table->json('repeats_weekly_on')->nullable()->comment('days of the week');
            $table->string('repeats_monthly_on')->nullable();
            $table->string('ends')->nullable();
            $table->date('end_date')->nullable();
            $table->json('layout')->nullable();
            $table->boolean('is_draft')->nullable()->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('email_templates');
    }
}
