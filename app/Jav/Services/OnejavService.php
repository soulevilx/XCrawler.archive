<?php

namespace App\Jav\Services;

use App\Core\Models\Download;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Events\OnejavReleaseCompleted;
use App\Jav\Models\Onejav;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class OnejavService implements ServiceInterface
{
    use HasAttributes;

    protected Onejav $model;

    public const SERVICE_LABEL = 'Onejav';

    public function __construct(protected OnejavCrawler $crawler)
    {
    }

    public function create(array $attribute = []): Onejav
    {
        $this->attributes = array_merge($this->attributes, $attribute);
        $this->model = Onejav::firstOrCreate(
            ['dvd_id' => $this->attributes['dvd_id']],
            $this->attributes
        );

        return $this->model;
    }

    public function daily()
    {
        $items = $this->crawler->daily();
        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->update($item);
        });

        return $items;
    }

    public function release()
    {
        $currentPage = ApplicationService::getConfig('onejav', 'current_page', 1);

        $items = $this->crawler->getItems('new', ['page' => $currentPage]);

        $items->each(function ($item) {
            $this->update($item);
        });

        ++$currentPage;

        if ((int) ApplicationService::getConfig('onejav', 'total_pages', config('services.onejav.total_pages')) < $currentPage) {
            $currentPage = 1;
            Event::dispatch(new OnejavReleaseCompleted());
        }

        ApplicationService::setConfig('onejav', 'current_page', $currentPage);

        return $items;
    }

    public function item(Model $model): Onejav
    {
        return $this->refetch($model);
    }

    private function update(\ArrayObject $item)
    {
        Onejav::updateOrCreate(
            ['url' => $item->url],
            $item->getArrayCopy()
        );
    }

    public function download(Onejav $onejav): bool
    {
        $onejav = $this->refetch($onejav);
        $fileName = config('services.jav.download_dir') . '/' . basename($onejav->torrent);
        $file = fopen($fileName, 'wb');
        $response = app(Client::class)->request(
            'GET',
            $onejav->torrent,
            [
                'sink' => $file,
                'base_uri' => Onejav::BASE_URL,
            ]
        );

        if ($response->getStatusCode() === 200) {
            Download::create([
                'model_id' => $onejav->id,
                'model_type' => Onejav::class,
            ]);

            session()->flash(
                'messages',
                [
                    ['message' => 'Download completed:  ' . $fileName, 'type' => 'primary'],
                ]
            );
            return true;
        }

        return false;
    }

    public function refetch(Onejav $onejav): Onejav
    {
        $item = $this->crawler->getItems($onejav->url)->first();
        $onejav->update($item->getArrayCopy());

        /**
         * @TODO
         * If refetch changing dvd_id we'll lost connect with Movie
         */
        return $onejav;
    }
}
