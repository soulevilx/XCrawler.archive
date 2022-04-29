<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcityIdolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xcity_idols', function (Blueprint $table) {
            $table->id();

            $table->string('url')->unique();
            $table->string('name')->nullable()->index();
            $table->string('cover')->nullable();
            $table->unsignedInteger('favorite')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->string('blood_type')->index()->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('breast')->nullable();
            $table->unsignedInteger('waist')->nullable();
            $table->unsignedInteger('hips')->nullable();
            $table->string('skill')->nullable();
            $table->string('other')->nullable();

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
        Schema::dropIfExists('xcity_idols');
    }
}
