<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('type')->nullable()->comment('what type of action. ie. changed status, uploaded file, etc');
            $table->string('value')->nullable()->comment('value of the action. ie. completed');
            $table->unsignedBigInteger('relation_id')->nullable()->comment('id of the relation model. needed for indexing');
            $table->json('data')->nullable()->comment('extra field for needed data');
            $table->text('body')->nullable()->comment('used for comments');
            $table->json('mention_ids')->nullable()->comment('list of user id that have been mentioned in comment');
            $table->morphs('actionable');
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
        Schema::dropIfExists('actions');
    }
}
