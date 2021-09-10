<?php

namespace App\Flickr\Tests\Traits;

trait HasPeopleMocker
{
    protected function loadPeopleMocker()
    {
        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getInfo',
                'POST',
                ['user_id' => '94529704@N02']
            )
            ->andReturn(
                json_encode($this->getPeopleInfo('94529704@N02'))
            );

        $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getPhotos',
                'POST',
                [
                    'user_id' => '94529704@N02',
                    'safe_search' => 3,
                    'per_page' => 500,
                    'page' => 1
                ]
            )
            ->andReturn(
                json_encode($this->getPeoplePhotos('94529704@N02'))
            );
    }

    private function getPeopleInfo(string $nsid)
    {
        $userName = $this->faker->unique->userName;
        return [
            'person' =>
                [
                    'id' => $nsid,
                    'nsid' => $nsid,
                    'ispro' => (int) $this->faker->boolean,
                    'can_buy_pro' => 1,
                    'iconserver' => '760',
                    'iconfarm' => 1,
                    'path_alias' => $this->faker->unique->slug,
                    'has_stats' => 0,
                    'gender' => 'M',
                    'ignored' => 0,
                    'contact' => 1,
                    'friend' => 0,
                    'family' => 0,
                    'revcontact' => 0,
                    'revfriend' => 0,
                    'revfamily' => 0,
                    'username' =>
                        array(
                            '_content' => $userName,
                        ),
                        'description' =>
                        array(
                            '_content' => $this->faker->text,
                        ),
                        'photosurl' =>
                        array(
                            '_content' => 'https://www.flickr.com/photos/' . $userName . '/',
                        ),
                        'profileurl' =>
                        array(
                            '_content' => 'https://www.flickr.com/people/' . $userName . '/',
                        ),
                        'mobileurl' =>
                        array(
                            '_content' => 'https://m.flickr.com/photostream.gne?id=' . $this->faker->numerify,
                        ),
                        'photos' =>
                        array(
                            'firstdatetaken' =>
                                array(
                                    '_content' => '2000-01-24 03:27:57',
                                ),
                            'firstdate' =>
                                array(
                                    '_content' => '1233572682',
                                ),
                            'count' =>
                                array(
                                    '_content' => '23136',
                                ),
                        ),
                        'has_adfree' => 0,
                        'has_free_standard_shipping' => 0,
                        'has_free_educational_resources' => 0,
                ],
            'stat' => 'ok',
        ];
    }

    private function getPeoplePhotos(string $nsid)
    {
        return [
            'photos' => [
                'page' => 1,
                'pages' => 1,
                'perpage' => 500,
                'total' => 358,
                'photo' => [
                    [
                        'id' => '50068037298',
                        'owner' => '94529704@N02',
                        'secret' =>
                            '4bacd5c629',
                        'server' => '65535',
                        'farm' => 66,
                        'title' => 'YX5A0674',
                        'ispublic' => 0,
                        'isfriend' => 1,
                        'isfamily' => 0,
                    ],
                ]
            ],
            'stat' => 'ok'
        ];
    }
}
