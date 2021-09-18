<?php

namespace App\Jav\Crawlers;

use App\Core\Client;
use App\Jav\Models\XCityIdol;
use ArrayObject;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class XCityIdolCrawler
{
    public function __construct(protected Client $client)
    {
    }

    public function getSubPages(): Collection
    {
        $response = $this->client->get(XCityIdol::INDEX_URL);

        if (!$response->isSuccessful()) {
            return collect();
        }

        $links = $response->getData()->filter('ul.itemStatus li a')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        return collect($links);
    }

    public function getItem(int $id, array $payload = []): ?ArrayObject
    {
        $response = $this->client->get(XCityIdol::INDEX_URL . 'detail/' . $id, $payload);

        if (!$response->isSuccessful()) {
            return null;
        }

        $item = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        $item->url = 'detail/' . $id . '/';
        if (0 === $response->getData()->filter('.itemBox h1')->count()) {
            return null;
        }
        $item->name = $response->getData()->filter('.itemBox h1')->text(null, false);
        $item->cover = $response->getData()->filter('.photo p.tn img')->attr('src');

        return $this->extractItemFields($response->getData(), $item);
    }

    public function getItemLinks(string $url, array $payload = []): Collection
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        if (0 !== $response->getData()->filter('.itemBox p.tn')->count()) {
            $links = $response->getData()->filter('.itemBox p.tn')->each(static function ($el) {
                return $el->filter('a')->attr('href');
            });

            return collect($links);
        }

        return collect($response->getData()->filter('.itemBox p.name a')->each(static function ($el) {
            return $el->filter('a')->attr('href');
        }));
    }

    public function getPages(string $url, array $payload = []): int
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return 1;
        }

        $nodes = $response->getData()->filter('ul.pageScrl li.next');

        if (0 === $nodes->count() || 0 === $nodes->previousAll()->filter('li a')->count()) {
            return 1;
        }

        return (int) $response->getData()->filter('ul.pageScrl li.next')->previousAll()->filter('li a')->text(null, false);
    }

    private function extractItemFields(Crawler $data, ArrayObject $item)
    {
        collect($data->filter('#avidolDetails dl.profile dd')->each(
            function (Crawler $node) use ($item) {
                $label = $node->children()->text();
                $value = str_replace($label, '', $node->text());
                $label = Str::slug($label, '_');

                switch ($label) {
                    case 'favorite':
                        $item->{$label} = (int) $value;

                        return;

                    case 'date_of_birth':
                        try {
                            $value = Carbon::createFromFormat('Y M d', $value);
                        } catch (InvalidFormatException) {
                            $value = null;
                        }

                        break;

                    case 'blood_type':
                        $value = trim(str_replace(['Type', '-', '_'], ['', '', '', ''], $value));
                        $value = empty($value) ? null : $value;

                        break;
                    case 'city_of_born':
                    case 'special_skill':
                    case 'other':
                        $value = trim($value);
                        $value = empty($value) ? null : $value;

                        break;

                    case 'height':
                        $value = trim($value);
                        if (!empty($value)) {
                            $value = str_replace(['cm'], [''], $value);
                            $item->{$label} = (int) $value;
                        } else {
                            $item->{$label} = null;
                        }

                        return;

                    case 'size':
                        $sizes = explode(' ', $value);
                        foreach ($sizes as $index => $size) {
                            switch ($index) {
                                case 0:
                                    $size = str_replace('B', '', $size);
                                    $size = explode('(', $size);
                                    $breast = empty(trim($size[0])) ? null : (int) $size[0];

                                    break;

                                case 1:
                                    $size = str_replace('W', '', $size);
                                    $size = explode('(', $size);
                                    $waist = empty(trim($size[0])) ? null : (int) $size[0];

                                    break;

                                case 2:
                                    $size = str_replace('H', '', $size);
                                    $size = explode('(', $size);
                                    $hips = empty(trim($size[0])) ? null : (int) $size[0];

                                    break;
                            }
                        }

                        $item->breast = $breast ?? null;
                        $item->waist = $waist ?? null;
                        $item->hips = $hips ?? null;

                        return;
                }

                $item->{$label} = $value;
            }
        ))->reject(static function ($value) {
            return null === $value;
        });

        return $item;
    }
}
