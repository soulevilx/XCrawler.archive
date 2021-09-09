<?php

namespace App\Flickr\Tests\Traits;

trait HasPeopleMocker
{
    protected function loadPeopleMocker()
    {
        return $this->flickrMocker->shouldReceive('requestJson')
            ->with(
                'flickr.people.getInfo',
                'POST',
                ['user_id' => '94529704@N02']
            )
            ->andReturn(
                json_encode($this->getPeopleInfo('94529704@N02'))
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
                    'ispro' => (int)$this->faker->boolean,
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
}
