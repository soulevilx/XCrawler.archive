<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlickrOnNewDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $builder = Schema::connection('flickr');

        if (!$builder->hasTable('flickr_contacts')) {
            $builder->create('flickr_contacts', function (Blueprint $table) {
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

        if (!$builder->hasTable('flickr_processes')) {
            $builder->create('flickr_processes', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('model_id');
                $table->string('model_type');
                $table->index(['model_id', 'model_type']);
                $table->string('step')->index();
                $table->string('state_code')->index();
                $table->foreign('state_code')->references('reference_code')->on('states');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!$builder->hasTable('flickr_photos')) {
            $builder->create('flickr_photos', function (Blueprint $table) {
                $table->id();

                $table->string('owner')->index();
                $table->foreign('owner')->references('nsid')->on('flickr_contacts');
                $table->string('secret')->nullable();
                $table->string('server')->nullable();
                $table->string('farm')->nullable();
                $table->string('title')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!$builder->hasTable('flickr_albums')) {
            $builder->create('flickr_albums', function (Blueprint $table) {
                $table->id();

                $table->string('owner')->index();
                $table->foreign('owner')->references('nsid')->on('flickr_contacts');
                $table->string('url')->nullable()->index();
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

        if (!$builder->hasTable('flickr_album_photos')) {
            $builder->create('flickr_album_photos', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('photo_id');
                $table->foreign('photo_id')->references('id')->on('flickr_photos');
                $table->unsignedBigInteger('album_id');
                $table->foreign('album_id')->references('id')->on('flickr_albums');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!$builder->hasTable('flickr_downloads')) {
            $builder->create('flickr_downloads', function (Blueprint $table) {
                $table->id();

                $table->string('model_id')->nullable();
                $table->string('model_type')->nullable();
                $table->string('name')->index();
                $table->string('path');
                $table->integer('total');
                $table->string('state_code')->index();
                $table->foreign('state_code')->references('reference_code')->on('states');

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!$builder->hasTable('flickr_download_items')) {
            $builder->create('flickr_download_items', function (Blueprint $table) {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
