<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Models\State;
use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Models\XCityVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class GetVideoLinks implements ShouldQueue
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
        $links = $crawler->getItemLinks([
            'from_date' => $this->data['from_date'],
            'to_date' => $this->data['to_date'],
            'num' => XCityVideo::PER_PAGE,
            'page' => $this->data['page']
        ]);

        foreach ($links as $link) {
            XCityVideo::firstOrCreate([
                'url' => $link,
            ], [
                'state_code' => State::STATE_INIT,
            ]);
        }
    }
}

