<?php

use App\Core\Services\Facades\Application;
use App\Jav\Services\OnejavService;
use App\Jav\Services\R18Service;
use App\Jav\Services\XCityIdolService;
use App\Jav\Services\XCityVideoService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Application::setSettings([
            'onejav' => [
                'base_url' => OnejavService::BASE_URL,
                'total_pages' => 8500,
            ],
            'r18' => [
                'base_url' => R18Service::BASE_URL,
                'urls' => [
                    'release' => '/videos/vod/movies/list/pagesize=30/price=all/sort=new/type=all',
                    'prime' => '/videos/channels/prime',
                    'playgirl' => '/videos/channels/playgirl',
                    'avstation' => '/videos/channels/avstation',
                    'dream' => '/videos/channels/dream',
                    's1' => '/videos/channels/s1/',
                    'moodyz' => '/videos/channels/moodyz',
                    'sod' => '/videos/channels/sod',
                ]
            ],
            'xcity_idols' => [
                'base_url' => XCityIdolService::BASE_URL,
                'sub_pages' => XCityIdolService::SUBPAGES,
            ],
            'xcity_videos' => [
                'base_url' => XCityVideoService::BASE_URL,
                'from_date' => '20010101'
            ],
            'core' => [
                'download_dir' => 'downloads'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
