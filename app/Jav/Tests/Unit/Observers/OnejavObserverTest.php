<?php

namespace App\Jav\Tests\Unit\Observers;

use App\Jav\Events\Onejav\OnejavDownloadCompleted;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class OnejavObserverTest extends JavTestCase
{
    use OnejavMocker;

    public function testRequestDownload()
    {
        Storage::fake('downloads');
        Event::fake([OnejavDownloadCompleted::class]);

        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')
            ->andReturn(new Response());
        app()->instance(Client::class, $client);

        $movie = Movie::factory()->create();
        $movie->requestDownload()->create();
        $onejav = Onejav::factory()->create([
            'dvd_id' => $movie->dvd_id,
            'url' => '/torrent/waaa088_1'
        ]);

        $driver = Storage::drive('downloads');
        $this->assertTrue($driver->exists('onejav/onejav.com_waaa088_1.torrent'));

        $this->assertDatabaseHas('downloads', [
            'model_id' => $onejav->id,
            'model_type' => Onejav::class,
        ]);

        Event::dispatch(OnejavDownloadCompleted::class, function (OnejavDownloadCompleted $event) use ($onejav) {
            return $event->onejav->is($onejav);
        });
    }
}
