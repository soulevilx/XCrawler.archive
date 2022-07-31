<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Core\Models\BaseMongo;
use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\Flickr\Contacts;
use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;

class FlickrServiceTest extends FlickrTestCase
{
    /**
     * @dataProvider dataProviderTestPeopleFailed
     * @return void
     */
    public function testPeopleFailed(string $userId, string $endpoint, int $code)
    {
        Event::fake(FlickrRequestFailed::class);

        try {
            $this->service->people()->getInfo($userId);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FlickrGeneralException::class, $exception);
        }
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE_NAME,
            'endpoint' => $endpoint,
            'is_succeed' => false,
            'response' => People::ERROR_MESSAGES_MAP[$code]

        ], BaseMongo::CONNECTION_NAME);

        Event::assertDispatched(FlickrRequestFailed::class, function ($event) use ($userId) {
            return $event->path === 'flickr.people.getInfo' && $event->params['user_id'] === $userId;
        });
    }

    public function testPeopleTokenException()
    {
        $this->expectException(TokenResponseException::class);
        $this->service->people()->getInfo('exception');
    }

    public function dataProviderTestPeopleFailed()
    {
        return [
            [
                'deleted',
                'flickr.people.getInfo',
                People::ERROR_CODE_USER_DELETED,
            ],
            [
                'null',
                'flickr.people.getInfo',
                People::ERROR_CODE_USER_NOT_FOUND,
            ],
        ];
    }

    public function testContacts()
    {
        $this->assertEquals($this->totalContacts, $this->service->contacts()->getListAll()->count());
        $this->assertEquals(
            Contacts::PER_PAGE,
            $this->service->contacts()->getList(
                null,
                null,
                Contacts::PER_PAGE
            )['contact']->count()
        );
    }

    public function testContactsFailed()
    {
        Event::fake(FlickrRequestFailed::class);
        try {
            $this->service->contacts()->getList(null, null, 9999);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FlickrGeneralException::class, $exception);
        }

        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE_NAME,
            'endpoint' => 'flickr.contacts.getList',
            'error' => 'The possible values are: name and time.'
        ], BaseMongo::CONNECTION_NAME);
        Event::assertDispatched(FlickrRequestFailed::class);
    }

    public function testPeopleInfo()
    {
        $this->assertEquals($this->nsid, $this->service->people()->getInfo($this->nsid)['nsid']);
        $this->assertEquals('SoulEvilX', $this->service->people()->getInfo($this->nsid)['username']);
        $this->assertEquals(150, $this->service->people()->getPhotos(
            $this->nsid,
            3,
            null,
            null,
            null,
            null,
            null,
            null,
            150
        )['photo']->count());
        $this->assertEquals(358, $this->service->people()->getPhotosAll(
            $this->nsid,
            3,
            null,
            null,
            null,
            null,
            null,
            null,
            150
        )->count());
    }

    public function testPeopleInfoUserDeleted()
    {
        Event::fake([
            FlickrRequestFailed::class,
            UserDeleted::class,
        ]);
        try {
            FlickrContact::factory()->create(['nsid' => 'deleted']);
            $this->service->people()->getInfo('deleted');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FlickrGeneralException::class, $exception);
        }

        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE_NAME,
            'endpoint' => 'flickr.people.getInfo',
            'error' => People::ERROR_MESSAGES_MAP[People::ERROR_CODE_USER_DELETED],
        ], BaseMongo::CONNECTION_NAME);

        Event::dispatched(FlickrRequestFailed::class);
        Event::dispatched(UserDeleted::class);
    }

    public function testPeopleGetPhotosUserDeleted()
    {
        $this->expectException(FlickrGeneralException::class);
        $this->assertEmpty($this->service->people()->getPhotos('deleted'));
    }

    public function testPhotos()
    {
        $sizes = $this->service->photos()->getSizes(50068037298);
        $this->assertEquals(11, $sizes['size']->count());
    }

    public function testPhotoSets()
    {
        $photosets = $this->service->photosets()->getList($this->nsid);

        $this->assertEquals(23, $photosets['total']);
        $this->assertEquals(23, $photosets['photoset']->count());

        $photosets = $this->service->photosets()->getListAll($this->nsid);
        $this->assertEquals(23, $photosets->count());
    }

    public function testPhotoSetsNotFound()
    {
        try {
            $this->service->photosets()->getInfo(999, 'deleted');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FlickrGeneralException::class, $exception);
        }

        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE_NAME,
            'endpoint' => 'flickr.photosets.getInfo',

        ], BaseMongo::CONNECTION_NAME);
    }

    public function testPhotoSetsPhotos()
    {
        $photos = $this->service->photosets()->getAllPhotos('72157688392979533', '94529704@N02', null, null, 50);
        $this->assertEquals(98, $photos->count());
    }

    public function testUrls()
    {
        $result = $this->service->urls()->lookupUser($this->faker->url);
        $this->assertEquals('51838687@N07', $result['id']);
        $this->assertEquals('ANGUS PHOTOGRAPHY', $result['username']);
    }

    public function testGetAuthUrl()
    {
        $this->assertInstanceOf(Uri::class, $this->service->getAuthUrl());
    }
}
