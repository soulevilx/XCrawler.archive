<?php

namespace App\Flickr\Tests\Traits;

trait HasContactsMocker
{
    protected function loadContactsMocker()
    {
        $data = [
            'params' => ['per_page' => 1000],
            'pages' => 2,
            'total' => 1105
        ];
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.contacts.getList',
                'POST',
                $data['params']
            )
            ->andReturn(
                json_encode($this->getContacts(
                    1,
                    $data['pages'],
                    $data['params']['per_page'],
                    $data['total']
                ))
            );
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.contacts.getList',
                'POST',
                ['per_page' => 200]
            )
            ->andReturn(
                json_encode($this->getContacts(
                    1,
                    $data['pages'],
                    200,
                    $data['total']
                ))
            );
        for ($page = 2; $page <= $data['pages']; $page++) {
            $this->flickrMocker->shouldReceive('requestJson')
                ->with(
                    'flickr.contacts.getList',
                    'POST',
                    $data['params'] + ['page' => $page]
                )
                ->andReturn(
                    json_encode($this->getContacts(
                        $page,
                        $data['pages'],
                        $data['params']['per_page'],
                        $data['total']
                    ))
                );
        }
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
