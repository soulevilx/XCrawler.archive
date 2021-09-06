<?php

namespace App\Flickr\Tests;

use App\Flickr\Services\FlickrService;
use OAuth\ServiceFactory;
use Tests\TestCase;

class FlickrTestCase extends TestCase
{
    protected FlickrService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = __DIR__ . '/Fixtures';

        $serviceMocker = \Mockery::mock(ServiceFactory::class);
        $flickrMocker = \Mockery::mock(ServiceFactory::class);

        $list = [
            'flickr.contacts.getList' => [
                'method' => 'getContacts',
                'params' => ['per_page' => 1000],
                'pages' => 2,
                'total' => 1105
            ],
        ];

        foreach ($list as $api => $data) {
            for ($page = 1; $page <= $data['pages']; $page++) {
                $flickrMocker->shouldReceive('requestJson')
                    ->with(
                        $api,
                        'POST',
                        $data['params'] + ['page' => $page]
                    )
                    ->andReturn(
                        json_encode($this->{$data['method']}(
                            $page,
                            $data['pages'],
                            $data['params']['per_page'],
                            $data['total']
                        ))
                    );
            }
        }

        $serviceMocker->shouldReceive('createService')
            ->andReturn($flickrMocker);

        app()->instance(ServiceFactory::class, $serviceMocker);
        $this->service = app(FlickrService::class);
    }

    private function getContacts(int $page, int $pages, int $perPage, int $total)
    {
        $photos = $total - ($perPage * ($page - 1));
        if ($photos > $perPage) {
            $photos = $perPage;
        }

        $contacts['contacts'] = [
            'page' => $page,
            'pages' => $pages,
            'per_page' => $perPage,
            'perpage' => $perPage,
            'total' => $total
        ];

        for ($index = 1; $index <= $photos; $index++) {
            $contacts['contacts']['contact'][] = [
                'nsid' => $this->faker->unique->uuid,
                'username' => $this->faker->userName,
                'iconserver' => $this->faker->numerify('#####'),
                'iconfarm' => $this->faker->numerify('#####'),
                'ignored' => $this->faker->boolean,
                'rev_ignored' => $this->faker->boolean,
                'realname' => $this->faker->name,
                'friend' => $this->faker->boolean,
                'family' => $this->faker->boolean,
                'path_alias' => $this->faker->unique->slug,
                'location' => $this->faker->country
            ];
        }

        $contacts['stat'] = 'ok';
        return $contacts;
    }
}
