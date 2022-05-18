<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Jobs\BaseJob;
use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Models\State;
use App\Jav\Models\XCityVideo;

class GetVideoLinks extends BaseJob
{
    protected string $service = 'xcity';

    public function __construct(public array $data)
    {
    }

    public function handle(XCityVideoCrawler $crawler)
    {
        $links = $crawler->getItemLinks([
            'from_date' => $this->data['from_date'],
            'to_date' => $this->data['to_date'],
            'num' => XCityVideo::PER_PAGE,
            'page' => $this->data['page']
        ]);

        foreach ($links as $link) {
            XCityVideo::firstOrCreate(['url' => $link], ['state_code' => State::STATE_INIT]);
        }
    }
}
