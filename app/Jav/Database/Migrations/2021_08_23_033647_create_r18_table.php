<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateR18Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r18', function (Blueprint $table) {
            $table->id();

            $table->string('url')->unique();
            $table->string('cover')->nullable();
            $table->text('title')->nullable();
            $table->date('release_date')->nullable();
            $table->string('maker')->index()->nullable();
            $table->integer('runtime')->nullable();
            $table->string('director')->index()->nullable();
            $table->string('studio')->index()->nullable();
            $table->string('label')->index()->nullable();
            $table->json('genres')->nullable();
            $table->json('performers')->nullable();
            $table->json('channels')->nullable();
            $table->string('content_id')->index();
            $table->string('dvd_id')->index()->nullable();
            $table->string('series')->index()->nullable();
            $table->string('languages')->nullable();
            $table->json('sample')->nullable();
            $table->json('images')->nullable();
            $table->json('gallery')->nullable();
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
        Schema::dropIfExists('r18');
    }
}
