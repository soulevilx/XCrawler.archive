<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\WordPressPost;
use App\Jav\Models\Movie;
use App\Jav\Tests\JavTestCase;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @internal
 * @coversNothing
 */
class WordPressPostModelTest extends JavTestCase
{
    /**
     * @covers \App\Core\Models\WordPressPost
     */
    public function testModel()
    {
        $movie = Movie::factory()->create();
        $wordPress = WordPressPost::factory()->create([
            'model_id' => $movie->id,
            'model_type' => Movie::class,
        ]);

        $this->assertInstanceOf(MorphTo::class, $wordPress->model());
        $this->assertInstanceOf(Movie::class, $wordPress->model);
    }
}
