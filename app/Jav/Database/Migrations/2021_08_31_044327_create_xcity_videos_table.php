<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcityVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xcity_videos', function (Blueprint $table) {
            $table->id();

            $table->text('name')->nullable();
            $table->string('url')->unique();
            $table->string('cover')->nullable();
            $table->dateTime('sales_date')->nullable();
            $table->dateTime('release_date')->nullable();

            $table->string('item_number')->unique();
            $table->string('dvd_id')->unique();

            $table->json('genres')->nullable();

            $table->json('actresses')->nullable();
            $table->text('description')->nullable();

            $table->integer('running_time')->nullable();

            $table->string('director')->nullable();

            $table->string('label')->nullable();
            $table->string('marker')->nullable();

            $table->string('studio')->nullable();

            $table->string('channel')->nullable();
            $table->string('series')->nullable();

            $table->json('gallery')->nullable();

            $table->string('sample')->nullable();
            $table->integer('favorite')->nullable();
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
        Schema::dropIfExists('xcity_videos');
    }
}
