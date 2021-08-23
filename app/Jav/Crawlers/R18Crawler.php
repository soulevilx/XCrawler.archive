<?php

namespace App\Jav\Crawlers;

use App\Core\Client;
use Exception;
use Illuminate\Support\Collection;

class R18Crawler
{
    public function __construct(protected Client $domClient, protected Client $jsonClient)
    {
    }

    public function getItem(string $url, array $payload = []): ?array
    {
        $response = $this->jsonClient->get($url, $payload);

        if (!$response->isSuccessful()) {
            return null;
        }

        if ('OK' !== $response->getData()['status']) {
            return null;
        }

        return $response->getData()['data'];
    }

    public function getItemLinks(string $url, array $payload = []): Collection
    {
        $response = $this->domClient->get($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect($response->getData()->filter('.main .cmn-list-product01 li.item-list')->each(
            function ($el) {
                if (null === $el->attr('data-content_id')) {
                    return false;
                }

                $itemId = $el->attr('data-content_id');

                return [
                    'url' => 'https://www.r18.com/videos/vod/movies/detail/-/id='.$itemId,
                    'content_id' => $itemId,
                ];
            }
        ))->reject(function ($value) {
            return false === $value;
        })->unique();
    }

    public function getPages(string $url, array $payload = []): int
    {
        $response = $this->domClient->get($url, $payload);

        if (!$response->isSuccessful()) {
            return 1;
        }

        try {
            return (int) $response->getData()->filter('li.next')->previousAll()->filter('a')->text();
        } catch (Exception) {
            return 1;
        }
    }
}
