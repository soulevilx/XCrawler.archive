<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use App\Jav\Tests\JavTestCase;

class PerformerModelTest extends JavTestCase
{
    public function testRelationship()
    {
        /**
         * @var Movie $movie
         */
        $movie = Movie::factory()->create();
        $performer = Performer::factory()->create();

        $movie->performers()->syncWithoutDetaching([
            $performer->id
        ]);

        $this->assertTrue($performer->refresh()->movies->first()->is($movie));
    }
}
