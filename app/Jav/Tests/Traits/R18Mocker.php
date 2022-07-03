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

    public function boot()
    {
        $this->mocks = [
           [
               'args' => [R18::MOVIE_LIST_URL, []],
               'andReturn' => 'R18/movie_list.html',
           ],
           [
               'args' => [R18::MOVIE_LIST_URL . '/page=1', []],
               'andReturn' => 'R18/movie_list.html',
           ],
           [
               'args' => [R18::MOVIE_LIST_URL . '/page=2', []],
               'andReturn' => 'R18/movie_list.html',
           ],
           [
               'args' => ['videos/rankings/movies/?type=daily', []],
               'andReturn' => 'R18/daily_ranking.html',
           ],
           [
               'args' => ['/api/v4f/contents/0', []],
               'andReturn' => null,
               'succeed' => false,
               'response' => app(JsonResponse::class)
           ],
        ];

        foreach (['s1', 'moodyz'] as $id) {
            $this->mocks[] = [
               'args' => ['videos/channels/' . $id, []],
               'andReturn' => 'R18/'. $id.'.html',
            ];
        }
        foreach (['rki00506', 'pfes00054'] as $id) {
            $this->mocks[] = [
               'args' => ['/api/v4f/contents/' . $id, []],
               'andReturn' => 'R18/item_'. $id. '.json',
                'response' => app(JsonResponse::class)
            ];
        }

        $this->service = $this->getService();
        $this->crawler = app(R18Crawler::class);
    }

    protected function getService(): R18Service
    {
        app()->instance(R18Crawler::class, new R18Crawler($this->xcrawlerMocker, $this->xcrawlerMocker));

        return app(R18Service::class);
    }
}
