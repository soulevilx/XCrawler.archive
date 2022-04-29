<?php

namespace Database\Seeders;

use App\Core\Services\Facades\Application;
use App\Jav\Services\OnejavService;
use App\Jav\Services\R18Service;
use App\Jav\Services\SCuteService;
use App\Jav\Services\XCityIdolService;
use App\Jav\Services\XCityVideoService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
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
            'scute' => [
                'base_url' => SCuteService::BASE_URL,
                'total_pages' => SCuteService::DEFAULT_TOTAL_PAGES,
            ],
            'core' => [
                'download_dir' => 'downloads'
            ]
        ];

        Application::setSettings($settings);
    }
}
