<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Services\OnejavService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jooservices\XcrawlerClient\Response\DomResponse;

trait OnejavMocker
{
    protected OnejavCrawler $crawler;

    protected function boot()
    {
        $now = Carbon::now()->format(OnejavService::DAILY_FORMAT);
        $this->mocks = [
            [
                'args' => ['invalid_date', []],
                'andReturn' => 'Onejav/july_22_2021_date.html',
            ],
            [
                'args' => ['failed', []],
                'andReturn' => null,
                'succeed' => false,
            ],
            #
        ];

        for ($index = 1; $index <= 5; $index++) {
            $this->mockResponse('new', $index);
            $this->mockResponse('popular', $index);
            $this->mockResponse('search/test', $index);

            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with($now, $index === 1 ? [] : ['page' => $index])
                ->andReturn(
                    $this->getSuccessfulMockedResponse(
                        app(DomResponse::class),
                        'Onejav/july_22_2021_page_'.$index.'.html'
                    )
                );
        }

        foreach (['waaa088_1', 'ipx873'] as $item) {
            $this->mocks[] = [
                'args' => ['/torrent/'.$item, []],
                'andReturn' => 'Onejav/'.$item.'.html',
            ];
        }

        // FC
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->withSomeOfArgs('fc')
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'Onejav/fc.html'));

        $this->service = $this->getService();
        $this->crawler = new OnejavCrawler($this->xcrawlerMocker);
    }

    private function mockResponse(string $name, ?int $page = null)
    {
        $fixtureFile = 'Onejav/'.Str::slug(Str::replace('/', '_', $name), '_').'_page_'.$page.'.html';

        $this->mocks[] = [
            'args' => [$name, $page === 1 ? [] : ['page' => $page]],
            'andReturn' => $fixtureFile,
        ];
        $this->mocks[] = $this->mocks[] = [
            'args' => [$name, ['page' => $page]],
            'andReturn' => $fixtureFile,
        ];
    }

    protected function getService(): OnejavService
    {
        app()->instance(OnejavCrawler::class, new OnejavCrawler($this->xcrawlerMocker));

        return app(OnejavService::class);
    }
}
