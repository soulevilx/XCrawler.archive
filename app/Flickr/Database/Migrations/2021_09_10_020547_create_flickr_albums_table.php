<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_albums', function (Blueprint $table) {
            $table->id();

            $table->string('owner')->index();
            $table->foreign('owner')->references('nsid')->on('flickr_contacts');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('primary')->nullable();
            $table->string('secret')->nullable();
            $table->unsignedBigInteger('server')->nullable();
            $table->unsignedBigInteger('farm')->nullable();
            $table->unsignedBigInteger('photos')->unsigned()->nullable();
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
        Schema::dropIfExists('flickr_albums');
    }
}
