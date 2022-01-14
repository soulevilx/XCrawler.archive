<?php

namespace App\Jav\Crawlers;

use App\Core\Client;
use Illuminate\Support\Collection;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

class SCuteCrawler
{
    public function __construct(protected Client $client)
    {
    }

    public function getItemLinks(string $url, array $payload = [], ResponseInterface &$response = null): Collection
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect($response->getData()->filter('.container article.contents a')->each(function ($el) {
            $cover = $el->filter('img');

            if (!$cover->count()) {
                return null;
            }

            return [
                'url' => $el->attr('href'),
                'cover' => $el->filter('img')->attr('src'),
            ];
        }))->reject(function ($value) {
            return $value === null;
        });
    }

    public function getItem(string $url, array $payload = []): Collection
    {
        $response = $this->client->get($url, $payload);

        if (!$response->isSuccessful()) {
            return collect();
        }

        return collect($response->getData()->filter('.my-container .my-item a')->each(function ($el) {
            return $el->attr('href');
        }));
    }
}
