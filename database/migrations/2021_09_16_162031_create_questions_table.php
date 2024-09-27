<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->longText('body');
            $table->text('tagline')->nullable();
            $table->string('type')->comment('detail, multi_choice, select, boolean');
            $table->json('choices')->nullable()->comment('for multi_choice, select');
            $table->boolean('add_file_uploader')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('is_onboarding')->nullable()->index();
            $table->unsignedBigInteger('client_id')->nullable()->index();
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
        Schema::dropIfExists('questions');
    }
}
