<?php

namespace App\Core\Tests\Feature\Console;

use App\Flickr\Services\FlickrService;
use OAuth\Common\Http\Uri\Uri;
use OAuth\OAuth1\Token\StdOAuth1Token;
use Tests\TestCase;

class IntegrateFlickrTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $token = new StdOAuth1Token();
        $token->setAccessToken($this->faker->uuid);
        $this->mocker = \Mockery::mock(FlickrService::class);
        $this->mocker->shouldReceive('getAuthUrl')
            ->andReturn(new Uri());
        $this->mocker->shouldReceive('retrieveAccessToken')
            ->andReturn($token);

        app()->instance(FlickrService::class, $this->mocker);

        $this->service = app(FlickrService::class);
    }

    public function testIntegrateFlickr()
    {
        $this->artisan('integrate:flickr')
            ->expectsQuestion('Enter code', $this->faker->text)
            ->assertExitCode(0);

        $this->assertDatabaseHas('integrations', [
            'service' => FlickrService::SERVICE_NAME,
        ], 'mongodb');
    }
}
