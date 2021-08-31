<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Jobs\XCity\InitVideoIndex;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class XCityVideoService implements ServiceInterface
{
    use HasAttributes;

    protected XCityVideo $video;

    public function __construct(protected XCityVideoCrawler $crawler, protected ApplicationService $service)
    {
    }

    public function release()
    {
        $fromDate = Carbon::createFromFormat(
            'Ymd',
            $this->service->get('xcity_video', 'from_date', config('services.xcity_video.from_date', 20010101))
        );

        $toDate = Carbon::createFromFormat(
            'Ymd',
            $this->service->get('xcity_video', 'from_date', config('services.xcity_video.from_date', 20010101))
        )->addDay();

        InitVideoIndex::dispatch([
            'from_date' => $fromDate->format('Ymd'),
            'to_date' => $toDate->format('Ymd'),
        ])->onQueue('crawling');

        $this->service->save('xcity_video', 'from_date', $toDate->format('Ymd'));
    }

    public function create(): Model
    {
        // TODO: Implement create() method.
    }

    public function item(Model $model): Model
    {
        // TODO: Implement item() method.
    }

    public function daily()
    {
        $fromDate = Carbon::now();
        InitVideoIndex::dispatch([
            'from_date' => $fromDate->format('Ymd'),
            'to_date' => $fromDate->addDay()->format('Ymd'),
        ])->onQueue('crawling');
    }
}
