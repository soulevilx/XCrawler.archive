<?php

namespace App\Jav\Jobs\XCity;

use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class InitVideoIndex implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

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
