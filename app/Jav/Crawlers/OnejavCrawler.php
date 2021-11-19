<?php

namespace App\Jav\Crawlers;

use App\Core\Client;
use ArrayObject;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class OnejavCrawler
{
    public function __construct(protected Client $client)
    {
    }

    public function getItems(string $url, array $payload = []): Collection
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect($response->getData()->filter('.container .columns')->each(function ($el) {
            return $this->parse($el);
        }));
    }

    public function daily(&$page = 1): Collection
    {
        $items = collect();
        $page = $this->getItemsRecursive($items, Carbon::now()->format('Y/m/d'));

        return $items;
    }

    public function popular(): Collection
    {
        $items = collect();
        $this->getItemsRecursive($items, 'popular');

        return $items;
    }

    public function search(string $keyword, string $by = 'search')
    {
        $items = collect();
        $this->getItemsRecursive($items, $by . '/' . urlencode($keyword));

        return $items;
    }

    public function getItemsRecursive(Collection &$items, string $url, array $payload = []): int
    {
        $currentPage = $payload['page'] ?? 1;
        $response = $this->client->get($url, $payload);

        if ($response->isSuccessful()) {
            $pageNode = $response->getData()->filter('a.pagination-link')->last();
            $lastPage = 0 === $pageNode->count() ? 1 : (int) $pageNode->text();

            $items = $items->merge(collect($response->getData()->filter('.container .columns')->each(function ($el) {
                return $this->parse($el);
            })));

            if (empty($payload) || $payload['page'] < $lastPage) {
                $lastPage = $this->getItemsRecursive($items, $url, ['page' => $currentPage + 1]);
            }

            return $lastPage;
        }

        return 1;
    }

    private function parse(Crawler $crawler): ArrayObject
    {
        $item = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);

        if ($crawler->filter('h5.title a')->count()) {
            $item->url = trim($crawler->filter('h5.title a')->attr('href'));
        }

        if ($crawler->filter('.columns img.image')->count()) {
            $item->cover = trim($crawler->filter('.columns img.image')->attr('src'));
        }

        if ($crawler->filter('h5 a')->count()) {
            $item->dvd_id = (trim($crawler->filter('h5 a')->text(null, false)));
            $item->dvd_id = implode('-', preg_split('/(,?\\s+)|((?<=[a-z])(?=\\d))|((?<=\\d)(?=[a-z]))/i', $item->dvd_id));
        }

        if ($crawler->filter('h5 span')->count()) {
            $item->size = trim($crawler->filter('h5 span')->text(null, false));

            if (str_contains($item->size, 'MB')) {
                $item->size = (float) trim(str_replace('MB', '', $item->size));
                $item->size /= 1024;
            } elseif (str_contains($item->size, 'GB')) {
                $item->size = (float) trim(str_replace('GB', '', $item->size));
            }
        }

        // Always use href because it'll never change but text will be
        $item->date = $this->convertStringToDateTime(trim($crawler->filter('.subtitle.is-6 a')->attr('href')));
        $item->genres = collect($crawler->filter('.tags .tag')->each(
            function ($genres) {
                return trim($genres->text(null, false));
            }
        ))->reject(function ($value) {
            return empty($value);
        })->unique()->toArray();

        $description = $crawler->filter('.level.has-text-grey-dark');
        $item->description = $description->count() ? trim($description->text(null, false)) : null;
        $item->description = preg_replace("/\r|\n/", '', $item->description);

        $item->performers = collect($crawler->filter('.panel .panel-block')->each(
            function ($performers) {
                return trim($performers->text(null, false));
            }
        ))->reject(function ($value) {
            return empty($value);
        })->unique()->toArray();

        $item->torrent = trim($crawler->filter('.control.is-expanded a')->attr('href'));

        return $item;
    }

    private function convertStringToDateTime(string $date): ?DateTime
    {
        if (!$dateTime = DateTime::createFromFormat('Y/m/j', trim($date, '/'))) {
            return null;
        }

        return $dateTime;
    }
}
