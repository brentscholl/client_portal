<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('src')->nullable();
            $table->string('file_name')->nullable();
            $table->string('extension')->nullable();
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->text('caption')->nullable();
            $table->boolean('is_resource')->default(false);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->timestamps();
        });
        Schema::table('files', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
