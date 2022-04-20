<?php

namespace App\Jav\Tests\Unit\Crawlers;

use App\Jav\Crawlers\SCuteCrawler;
use App\Jav\Tests\JavTestCase;
use Jooservices\XcrawlerClient\Response\DomResponse;

class SCuteCrawlerTest extends JavTestCase
{
    protected SCuteCrawler $crawler;

    public function setUp(): void
    {
        parent::setUp();

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('contents', ['page' => 1])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'SCute/contents.html'));

        $this->xcrawlerMocker
            ->shouldReceive('get')
            ->with('item', [])
            ->andReturn($this->getSuccessfulMockedResponse(app(DomResponse::class), 'SCute/item.html'));

        app()->instance(SCuteCrawler::class, new SCuteCrawler($this->xcrawlerMocker));
        $this->crawler = app(SCuteCrawler::class);
    }

    public function testGetItemLinks()
    {
        $links = $this->crawler->getItemLinks('contents', ['page' => 1]);

        $this->assertEquals(31, $links->count());
        $this->assertEquals(
            'http://www.s-cute.com/contents/887_nozomi_02/',
            $links->first()['url']
        );
        $this->assertEquals(
            'http://static.s-cute.com/images/887_nozomi/887_nozomi_02/887_nozomi_02_800.jpg',
            $links->first()['cover']
        );
    }

    public function testGetItem()
    {
        $item = $this->crawler->getItem('item', []);
        $this->assertEquals(10, $item->count());
    }
}
