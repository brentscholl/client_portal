<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('model_id');
            $table->string('model_type');
            $table->json('settings');
            $table->timestamps();

            $table->unique(['model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
