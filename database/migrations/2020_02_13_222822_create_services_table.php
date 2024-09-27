<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->timestamps();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images');
        });

        DB::table('services')->insert([
            ['title' => 'Web Design', 'slug' => 'web-design'],
            ['title' => 'Logo Design', 'slug' => 'logo-design'],
            ['title' => 'SEO', 'slug' => 'seo'],
            ['title' => 'Video & Photography', 'slug' => 'video-photography'],
            ['title' => 'Rep Management', 'slug' => 'rep-management'],
            ['title' => 'Social Media', 'slug' => 'social-media'],
            ['title' => 'Traditional Media & Print', 'slug' => 'traditional-media-print'],
            ['title' => 'Digital Campaigns', 'slug' => 'digital-campaigns'],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
