<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_photos', function (Blueprint $table) {
            $table->id();

            $table->string('owner')->index();
            $table->foreign('owner')->references('nsid')->on('flickr_contacts');
            $table->string('secret')->nullable();
            $table->string('server')->nullable();
            $table->string('farm')->nullable();
            $table->string('title')->nullable();
            $table->smallInteger('ispublic')->nullable();
            $table->smallInteger('isfriend')->nullable();
            $table->smallInteger('isfamily')->nullable();
            $table->json('sizes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flickr_photos');
    }
}
