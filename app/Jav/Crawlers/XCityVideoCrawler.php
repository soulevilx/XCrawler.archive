<?php

namespace App\Jav\Crawlers;

use App\Core\XCrawlerClient;
use App\Jav\Models\XCityVideo;
use ArrayObject;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class XCityVideoCrawler
{
    public function __construct(protected XCrawlerClient $client)
    {
    }

    public function getItem(string $url, array $payload = []): ?ArrayObject
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return null;
        }

        $item = new ArrayObject([
            'url',
            'name',
            'cover',
            'gallery',
            'actresses',
            'genres',
        ], ArrayObject::ARRAY_AS_PROPS);
        $item->url = $url.'?'.http_build_query($payload);

        $item->name = $response->getData()->filter('#program_detail_title')->text(null, false);
        $item->cover = $response->getData()->filter('.photo p.tn a')->attr('href');

        $item->gallery = collect($response->getData()->filter('.part_1 a')->each(static function ($el) {
            return str_replace('scene/small/', '', $el->attr('href'));
        }))->unique()->toArray();

        $item->actresses = collect(
            $response->getData()->filter('.bodyCol ul li.credit-links a')->each(static function ($el) {
                return trim($el->text());
            })
        )->unique()->toArray();

        $item->genres = collect(
            $response->getData()->filter('.bodyCol ul li a.genre')->each(static function ($el) {
                return trim($el->text());
            })
        )->unique()->toArray();

        return $this->extractItemFields($response->getData(), $item);
    }

    public function getItemLinks(array $payload = []): Collection
    {
        $payload['num'] = $payload['num'] ?? XCityVideo::PER_PAGE;
        $response = $this->client->get(XCityVideo::INDEX_URL, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect($response->getData()->filter('.x-itemBox')->each(static function ($el) {
            return $el->filter('.x-itemBox-package a')->attr('href');
        }));
    }

    public function getPages(array $payload = []): int
    {
        $payload['num'] = $payload['num'] ?? XCityVideo::PER_PAGE;
        $response = $this->client->get(XCityVideo::INDEX_URL, $payload);

        if (!$response->isSuccessful()) {
            return 1;
        }

        $nodes = $response->getData()->filter('ul.pageScrl li.next');

        if (0 === $nodes->count() || 0 === $nodes->previousAll()->filter('li a')->count()) {
            return 1;
        }

        return (int) $response->getData()
            ->filter('ul.pageScrl li.next')->previousAll()
            ->filter('li a')
            ->text(null, false);
    }

    private function extractItemFields(Crawler $data, ArrayObject $item)
    {
        collect($data->filter('.bodyCol li')->each(
            function (Crawler $node) use ($item) {
                $label = $node->children()->text();
                $value = trim(str_replace($label, '', $node->text()));
                $label = str_replace('/', '_', $label);
                $label = Str::slug($label, '_');

                switch ($label) {
                    case 'favorite':
                        $item->{$label} = (int) $value;

                        return;

                    case 'sales_date':
                    case 'release_date':
                        $value = !empty($value) ? Carbon::createFromFormat('Y/m/d', $value) : null;

                        break;

                    case 'label_maker':
                        $item->maker = trim($node->filter('#program_detail_maker_name')->text());
                        $item->label = trim($node->filter('#program_detail_label_name')->text());

                        return;

                    case 'genres':
                        $value = $node->filter('a')->each(
                            function (Crawler $node) {
                                return trim($node->text());
                            }
                        );

                        break;

                    case 'running_time':
                        $value = (int) str_replace('min.', '', $value);

                        break;

                    case 'item_number':
                        $item->item_number = empty($value) ? null : $value;
                        $value = implode('-',
                            preg_split('/(,?\\s+)|((?<=[a-z])(?=\\d))|((?<=\\d)(?=[a-z]))/i', $value));
                        $item->dvd_id = empty($value) ? null : $value;

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
