<?php

namespace App\Jav\Tests\Unit\Routes;

use App\Jav\Models\Movie;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UrlGeneratorTest extends TestCase
{
    public function testMovieWithoutDvdId()
    {
        Event::fake();

        $movie = Movie::factory()->create([
            'dvd_id' => null,
        ]);
        $url = app(UrlGenerator::class)->route(
            'movie.show',
            ['movie' => $movie]
        );

        $this->assertEquals('http://localhost/jav/movies/' . $movie->id, $url);
    }

    public function testMovieWithDvdId()
    {
        Event::fake();

        $movie = Movie::factory()->create();
        $url = app(UrlGenerator::class)->route(
            'movie.show',
            ['movie' => $movie]
        );

        $this->assertEquals('http://localhost/jav/movies/' . $movie->id, $url);
    }
}
