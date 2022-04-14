<?php

namespace App\Jav\Crawlers;

use App\Core\Services\Facades\Application;
use App\Core\XCrawlerClient;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use Exception;
use Illuminate\Support\Collection;

class R18Crawler
{
    public function __construct(protected XCrawlerClient $domClient, protected XCrawlerClient $jsonClient)
    {
    }

    public function getItem(string $contentId, array $payload = []): ?array
    {
        $response = $this->jsonClient->get(R18::MOVIE_DETAIL_ENDPOINT . '/' . $contentId, $payload);

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

        return collect($response->getData()->filter('.main .cmn-list-product01 li')->each(
            function ($el) {

                if (null === $el->attr('data-content_id')) {
                    $href = $el->filter('a')->attr('href');
                    $data = array_reverse(explode('/', $href));
                    foreach ($data as $item) {
                        if (str_contains($item, 'id=')) {
                            $itemId = explode('=', $item)[1];
                        }
                    }
                } else {
                    $itemId = $el->attr('data-content_id');
                }

                if (!$itemId) {
                    return false;
                }

                return [
                    'url' => Application::getString(R18Service::SERVICE_NAME, 'base_url')
                        . '/videos/vod/movies/detail/-/id=' . $itemId,
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
