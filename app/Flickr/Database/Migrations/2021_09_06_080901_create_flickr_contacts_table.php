<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_contacts', function (Blueprint $table) {
            $table->id();

            $table->string('nsid')->unique();
            $table->integer('ispro')->nullable();
            $table->string('pro_badge')->nullable();
            $table->integer('expire')->nullable();
            $table->integer('can_buy_pro')->nullable();
            $table->string('iconserver')->nullable();
            $table->string('iconfarm')->nullable();
            $table->integer('ignored')->nullable();
            $table->string('path_alias')->nullable();
            $table->integer('has_stats')->nullable();
            $table->string('gender')->nullable();
            $table->integer('contact')->nullable();
            $table->integer('friend')->nullable();
            $table->integer('family')->nullable();
            $table->integer('revcontact')->nullable();
            $table->integer('revfriend')->nullable();
            $table->integer('revfamily')->nullable();
            $table->integer('rev_ignored')->nullable();
            $table->string('username')->nullable()->index();
            $table->string('realname')->nullable()->index();
            $table->string('mbox_sha1sum')->nullable();
            $table->string('location')->nullable();
            $table->json('timezone')->nullable();
            $table->text('description')->nullable();
            $table->string('photosurl')->nullable();
            $table->string('profileurl')->nullable();
            $table->string('mobileurl')->nullable();
            $table->json('photos')->nullable();
            $table->integer('photos_count')->unsigned()->nullable()->index();
            $table->string('state_code')->index();

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
        Schema::dropIfExists('flickr_contacts');
    }
}
