<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Core\Models\Integration;
use App\Core\Tyche\Flickr;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;
use OAuth\ServiceFactory;

class FlickrContactsTest extends FlickrTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Integration::create([
            'service' => FlickrService::SERVICE_NAME,
            'access_token' => $this->faker->uuid,
        ]);
    }

    public function testCommand()
    {
        Queue::fake();
        $this->artisan('flickr:contacts');
        Queue::assertPushed(FlickrContacts::class, function ($job) {
            return $job->queue === 'api';
        });
    }

    public function testCommandDuplicated()
    {
        $serviceMocker = \Mockery::mock(ServiceFactory::class);
        $this->flickrMocker = \Mockery::mock(ServiceFactory::class);

        $this->flickrMocker->shouldReceive('requestJson')
            ->withSomeOfArgs(
                'flickr.contacts.getList',
                'POST',
            )
            ->andReturn(
                $this->getFixture('contacts.duplicated.json')
            );

        $serviceMocker->shouldReceive('createService')
            ->andReturn($this->flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->artisan('flickr:contacts');

        $this->assertEquals(1, FlickrContact::count());
    }
}
