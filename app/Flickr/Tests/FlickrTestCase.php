<?php

namespace App\Flickr\Tests;

use App\Flickr\Services\FlickrService;
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

        $this->failed();

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


        /**
         * flickr.people.getPhotos
         */
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
        /**
         * User deleted case
         */
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => 'deleted',
                    'per_page' => 500,
                    'page' => 1,
                    'safe_search' => 3,
                ]
            )
            ->andReturn(json_encode(['stat' => 'fail', 'code' => FlickrService::ERROR_CODE_USER_DELETED]));

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
            ->withSomeOfArgs(
                'flickr.photos.getSizes',
                'POST',
            )
            ->andReturn($this->getFixture('sizes.json'));

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.photosets.getList',
                'POST',
                ['user_id' => '94529704@N02', 'page' => 1, 'per_page' => 500]
            )
            ->andReturn($this->getFixture('photosets.getList.json'));


        // Urls
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.urls.lookupUser',
                'POST',
                ['url' => 'https://www.flickr.com/photos/51838687@N07/albums/72157719703391487']
            )
            ->andReturn($this->getFixture('urls.lookupUser.json'));

        // Photosets
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.photosets.getInfo',
                'POST',
                [
                    'photoset_id' => 72157688392979533,
                    'user_id' => '94529704@N02'
                ]
            )
            ->andReturn($this->getFixture('photosets.getInfo.json'));
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.photosets.getInfo',
                'POST',
                [
                    'photoset_id' => 72157719703391487,
                    'user_id' => '51838687@N07'
                ]
            )
            ->andReturn($this->getFixture('photosets.getInfo.json'));

        for ($page = 1; $page <= 2; $page++) {
            $this->flickrMocker->shouldReceive('requestJson')
                ->with(
                    'flickr.photosets.getPhotos',
                    'POST',
                    ['photoset_id' => '72157688392979533', 'user_id' => '94529704@N02', 'page' => $page, 'per_page' => 50]
                )
                ->andReturn($this->getFixture('photosets.getPhotos_'. $page.'.json'));
            $this->flickrMocker->shouldReceive('requestJson')
                ->with(
                    'flickr.photosets.getPhotos',
                    'POST',
                    ['photoset_id' => '72157688392979533', 'user_id' => '94529704@N02', 'page' => $page, 'per_page' => 500]
                )
                ->andReturn($this->getFixture('photosets.getPhotos_'. $page.'.json'));
            $this->flickrMocker->shouldReceive('requestJson')
                ->with(
                    'flickr.photosets.getPhotos',
                    'POST',
                    ['photoset_id' => '72157719703391487', 'user_id' => '51838687@N07', 'page' => $page, 'per_page' => 50]
                )
                ->andReturn($this->getFixture('photosets.getPhotos_'. $page.'.json'));
            $this->flickrMocker->shouldReceive('requestJson')
                ->with(
                    'flickr.photosets.getPhotos',
                    'POST',
                    ['photoset_id' => '72157719703391487', 'user_id' => '51838687@N07', 'page' => $page, 'per_page' => 500]
                )
                ->andReturn($this->getFixture('photosets.getPhotos_'. $page.'.json'));
        }


        $serviceMocker->shouldReceive('createService')
            ->andReturn($this->flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->service = app(FlickrService::class);
    }

    private function failed()
    {
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.contacts.getList',
                'POST',
                ['fail' => true]
            )
            ->andReturn(
                json_encode(['stat' => 'fail'])
            );

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getInfo',
                'POST',
                ['user_id' => 'deleted']
            )
            ->andReturn(json_encode(['stat' => 'fail', 'code' => FlickrService::ERROR_CODE_USER_DELETED]));
    }
}
