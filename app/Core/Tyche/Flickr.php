<?php

namespace App\Core\Tyche;

use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use Faker\Generator;
use OAuth\Common\Http\Uri\Uri;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\ServiceFactory;

class Flickr
{
    private string $fixtures;

    private FlickrService $service;

    protected Generator $faker;

    public function __construct()
    {
        $this->faker = app(Generator::class);
        $this->fixtures = __DIR__ . '/Fixtures';

        $serviceMocker = \Mockery::mock(ServiceFactory::class);
        $this->flickrMocker = \Mockery::mock(ServiceFactory::class);
        $this->flickrMocker->shouldReceive('requestRequestToken')
            ->andReturn(new StdOAuth1Token());
        $this->flickrMocker->shouldReceive('getAuthorizationUri')
            ->andReturn(new Uri());

        $serviceMocker->shouldReceive('createService')
            ->andReturn($this->flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->service = app(FlickrService::class);
    }

    public function createContact(array $attributes = []): FlickrContact
    {
        return $this->service->contacts()->create(array_merge([
            'nsid' => $this->generateNsid(),
            'ispro' => $this->faker->boolean,
            'pro_badge' => null,
            'expire' => null,
            'can_buy_pro' => null,
            'iconserver' => null,
            'iconfarm' => null,
            'ignored' => null,
            'path_alias' => null,
            'has_stats' => null,
            'gender' => null,
            'contact' => null,
            'friend' => null,
            'family' => null,
            'revcontact' => null,
            'revfriend' => null,
            'revfamily' => null,
            'rev_ignored' => null,
            'username' => $this->faker->userName,
            'realname' => $this->faker->name,
            'mbox_sha1sum' => null,
            'location' => null,
            'timezone' => null,
            'description' => null,
            'photosurl' => null,
            'profileurl' => null,
            'mobileurl' => null,
            'photos' => null,
            'photos_count' => $this->faker->numberBetween(),
        ], $attributes));
    }

    public function generateNsid(): string
    {
        return $this->faker->uuid;
    }
}
