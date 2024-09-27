<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('teams')->insert([
            ['title' => 'Sales', 'slug' => 'sales'],
            ['title' => 'Copy', 'slug' => 'copy'],
            ['title' => 'Design', 'slug' => 'design'],
            ['title' => 'Development', 'slug' => 'dev'],
            ['title' => 'Marketing Advisor', 'slug' => 'marketing-advisor'],
            ['title' => 'Production', 'slug' => 'production'],
            ['title' => 'Social', 'slug' => 'social'],
            ['title' => 'Merchandise', 'slug' => 'merch'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }

}
