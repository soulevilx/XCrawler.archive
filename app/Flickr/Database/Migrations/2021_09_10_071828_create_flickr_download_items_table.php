<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrDownloadItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('flickr')->create('flickr_download_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('download_id')->index();
            $table->foreign('download_id')->references('id')->on('flickr_downloads');
            $table->unsignedBigInteger('photo_id')->index();
            $table->foreign('photo_id')->references('id')->on('flickr_photos');

            $table->string('state_code')->index();
            $table->foreign('state_code')->references('reference_code')->on('states');

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
        Schema::dropIfExists('flickr_download_items');
    }
}
