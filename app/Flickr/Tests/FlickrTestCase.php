<?php

namespace App\Flickr\Tests;

use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\Traits\HasContactsMocker;
use App\Flickr\Tests\Traits\HasPeopleMocker;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use OAuth\ServiceFactory;
use Tests\TestCase;

class FlickrTestCase extends TestCase
{
    protected FlickrService $service;
    use HasContactsMocker;
    use HasPeopleMocker;

    protected LegacyMockInterface|MockInterface|ServiceFactory $flickrMocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__ . '/Fixtures';

        $serviceMocker = \Mockery::mock(ServiceFactory::class);
        $this->flickrMocker = \Mockery::mock(ServiceFactory::class);

        $list = [
            'loadContactsMocker',
            'loadPeopleMocker',
        ];

        foreach ($list as $mocker) {
            $this->{$mocker}();
        }

        $serviceMocker->shouldReceive('createService')
            ->andReturn($this->flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->service = app(FlickrService::class);
    }
}
