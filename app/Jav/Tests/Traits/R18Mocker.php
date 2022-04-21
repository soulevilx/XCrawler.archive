<?php

namespace App\Jav\Tests\Traits;

use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;

trait R18Mocker
{
    protected R18Crawler $crawler;

    protected function loadR18Mocker()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL, [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL . '/page=1', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with(R18::MOVIE_LIST_URL . '/page=2', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/movie_list.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('videos/rankings/movies/?type=daily', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/daily_ranking.html'));

        foreach (['s1', 'moodyz'] as $id) {
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('videos/channels/' . $id, [])
                ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'R18/'. $id.'.html'));
        }


        foreach (['rki00506', 'pfes00054'] as $id) {
            $this->xcrawlerMocker
                ->shouldReceive('get')
                ->with('/api/v4f/contents/' . $id, [])
                ->andReturn($this->getSuccessfulMockedResponse(app(JsonResponse::class), 'R18/item_'. $id. '.json'));
        }

        $this->failed();

        $this->service = $this->getService();
        $this->crawler = app(R18Crawler::class);
    }

    private function failed()
    {
        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('/api/v4f/contents/0', [])
            ->andReturn($this->getErrorMockedResponse(app(JsonResponse::class)));
    }

    protected function getService(): R18Service
    {
        app()->instance(R18Crawler::class, new R18Crawler($this->xcrawlerMocker, $this->xcrawlerMocker));

        return app(R18Service::class);
    }
}
