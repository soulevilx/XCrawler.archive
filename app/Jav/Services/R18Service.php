<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Events\R18\R18DailyCompleted;
use App\Jav\Events\R18\R18ReleaseCompleted;
use App\Jav\Models\R18;
use App\Jav\Repositories\R18Repository;
use App\Jav\Services\Traits\HasAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class R18Service
{
    use HasAttributes;

    protected R18 $model;

    public const SERVICE_NAME = 'r18';
    public const BASE_URL = 'https://www.r18.com';

    public function __construct(protected R18Crawler $crawler, protected R18Repository $repository)
    {
    }

    public function create(array $attributes): R18
    {
        return $this->repository->updateOrCreate([
            'url' => $attributes['url'],
            'content_id' => $attributes['content_id'],
        ], $attributes + ['state_code' => State::STATE_INIT]);
    }

    public function item(Model $model): R18
    {
        return $this->refetch($model);
    }

    public function release(string $url, string $type)
    {
        /**
         * Release only fetch links another job will fetch detail later.
         */
        $key = $type . '_current_page';
        $currentPage = Application::getInt(R18Service::SERVICE_NAME, $key, 1);

        $items = $this->crawler->getItemLinks($url . '/page=' . $currentPage);

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->create($item);
        });

        ++$currentPage;
        if ((int)Application::getSetting(R18Service::SERVICE_NAME, $type . '_total_pages', 2000) < $currentPage) {
            $currentPage = 1;
            Event::dispatch(new R18ReleaseCompleted);
        }

        Application::setSetting(R18Service::SERVICE_NAME, $key, $currentPage);

        return $items;
    }

    public function daily(string $url): Collection
    {
        /**
         * Make sure we fetch page 1 to get latest release while `release` fetching older.
         */
        $items = $this->crawler->getItemLinks($url . '/page=1');
        $items->each(function ($item) {
            $this->create($item);
        });

        Event::dispatch(new R18DailyCompleted($items));

        return $items;
    }

    public function refetch(R18 $model): R18
    {
        // Can't get item
        if (!$item = $this->crawler->getItem($model->content_id)) {
            return $model;
        }

        $item['runtime'] = $item['runtime_minutes'];
        $item['release_date'] = Carbon::createFromFormat('Y-m-d H:m:s', $item['release_date']);

        $item['maker'] = $item['maker']['name'] ?? null;
        $item['label'] = $item['label']['name'] ?? null;

        $item['series'] = $item['series'] ? $item['series']['name'] : [];

        if (is_array($item['categories'])) {
            foreach ($item['categories'] as $genre) {
                $item['genres'][] = $genre['name'];
            }
        }

        if (is_array($item['actresses'])) {
            foreach ($item['actresses'] as $performer) {
                $item['performers'][] = $performer['name'];
            }
        }

        if (is_array($item['channels'])) {
            foreach ($item['channels'] as $channel) {
                $item['channels'][] = $channel['name'];
            }
        }

        //$item['url'] = $item['detail_url'];
        $item['cover'] = $item['cover'] ?? $item['images']['jacket_image']['large'];

        if ($item) {
            $model->update($item);
        }

        return $model;
    }

    public function getItems(int $limit, int $id = null): Collection
    {
        return $this->repository->getItemsByState($limit, $id);
    }
}
