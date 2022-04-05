<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
use App\Jav\Tests\JavTestCase;

class GenreModelTest extends JavTestCase
{
    public function testRelationship()
    {
        /**
         * @var Movie $movie
         */
        $movie = Movie::factory()->create();
        $genre = Genre::factory()->create();

        $movie->genres()->syncWithoutDetaching([
            $genre->id
        ]);

        $this->assertTrue($genre->refresh()->movies->first()->is($movie));
    }
}
