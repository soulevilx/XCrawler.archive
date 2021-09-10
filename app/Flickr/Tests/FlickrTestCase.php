<?php

namespace App\Flickr\Tests;

use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\Traits\HasContactsMocker;
use App\Flickr\Tests\Traits\HasPeopleMocker;
use App\Flickr\Tests\Traits\HasPhotosMocker;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use OAuth\ServiceFactory;
use Tests\TestCase;

class FlickrTestCase extends TestCase
{
    protected FlickrService $service;

    protected LegacyMockInterface|MockInterface|ServiceFactory $flickrMocker;

    protected int $totalContacts = 1108;
    protected string $nsid = '94529704@N02';

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__ . '/Fixtures';

        $serviceMocker = \Mockery::mock(ServiceFactory::class);
        $this->flickrMocker = \Mockery::mock(ServiceFactory::class);

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.contacts.getList',
                'POST',
                ['per_page' => 1000]
            )
            ->andReturn(
                $this->getFixture('contacts.getList_1.json')
            );
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.contacts.getList',
                'POST',
                ['per_page' => 1000, 'page' => 2]
            )
            ->andReturn(
                $this->getFixture('contacts.getList_2.json')
            );
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getInfo',
                'POST',
                ['user_id' => $this->nsid]
            )
            ->andReturn($this->getFixture('people.getInfo.json'));

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => $this->nsid,
                    'per_page' => 150,
                    'page' => 1,
                    'safe_search' => 3,
                ]
            )
            ->andReturn($this->getFixture('people.getPhotos_1.json'));
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => $this->nsid,
                    'per_page' => 150,
                    'page' => 2,
                    'safe_search' => 3,
                ]
            )
            ->andReturn($this->getFixture('people.getPhotos_2.json'));
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => $this->nsid,
                    'per_page' => 150,
                    'page' => 3,
                    'safe_search' => 3,
                ]
            )
            ->andReturn($this->getFixture('people.getPhotos_3.json'));
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => $this->nsid,
                    'per_page' => 500,
                    'page' => 1,
                    'safe_search' => 3,
                ]
            )
            ->andReturn($this->getFixture('people.getPhotos500.json'));

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.photos.getSizes',
                'POST',
                ['photo_id' => 50068037298]
            )
            ->andReturn($this->getFixture('sizes.json'));

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.photosets.getList',
                'POST',
                ['user_id' => '94529704@N02', 'page' => 1, 'per_page' => 500]
            )
            ->andReturn($this->getFixture('photosets.getList.json'));

        $serviceMocker->shouldReceive('createService')
            ->andReturn($this->flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->service = app(FlickrService::class);
    }
}
