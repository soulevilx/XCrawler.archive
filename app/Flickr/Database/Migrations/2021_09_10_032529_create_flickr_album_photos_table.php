<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrAlbumPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_album_photos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('photo_id');
            $table->foreign('photo_id')->references('id')->on('flickr_photos');
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')->references('id')->on('flickr_albums');

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
        Schema::dropIfExists('photo_album');
    }
}
