<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Jobs\BaseJob;
use App\Jav\Crawlers\XCityVideoCrawler;

class InitVideoIndex extends BaseJob
{
    protected string $serviceName = 'xcity';

    public function __construct(public array $data)
    {
    }

    public function handle(XCityVideoCrawler $crawler)
    {
        $pages = $crawler->getPages([
            'from_date' => $this->data['from_date'],
            'to_date' => $this->data['to_date'],
        ]);

        for ($page = 1; $page <= $pages; $page++) {
            GetVideoLinks::dispatch(
                [
                    'from_date' => $this->data['from_date'],
                    'to_date' => $this->data['to_date'],
                    'page' => $page
                ]
            )->onQueue('crawling');
        }
    }
}
