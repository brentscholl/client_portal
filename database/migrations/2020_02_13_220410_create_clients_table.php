<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->float('monthly_budget', 11, 2)->nullable();
            $table->float('annual_budget', 11, 2)->nullable();
            $table->unsignedBigInteger('primary_contact')->nullable();
            $table->string('avatar')->nullable();
            $table->string('website_url')->nullable();
            $table->enum('archived', ['0','1'])->default(0);
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
        Schema::dropIfExists('clients');
    }
}
