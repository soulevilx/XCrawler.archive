<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\WordPressPost;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;


class MovieModelTest extends JavTestCase
{
    public function testModel()
    {
        $movie = Movie::factory()->create();

        $this->assertNull($movie->wordpress);
        $this->assertNull($movie->onejav);
        $this->assertNull($movie->r18);

        WordPressPost::factory()->create([
            'model_id' => $movie->id,
            'model_type' => Movie::class,
            'title' => $movie->dvd_id,
        ]);
        Onejav::factory()->create([
            'dvd_id' => $movie->dvd_id,
        ]);
        R18::factory()->create([
            'dvd_id' => $movie->dvd_id,
        ]);

        $movie->refresh();
        $this->assertInstanceOf(WordPressPost::class, $movie->wordpress);
        $this->assertInstanceOf(Onejav::class, $movie->onejav);
        $this->assertInstanceOf(R18::class, $movie->r18);
    }
}
