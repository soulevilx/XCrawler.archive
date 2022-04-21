<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Models\State;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use App\Jav\Models\Movie;
use App\Jav\Services\WordPressService;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Mail;

class WordPressPostServiceTest extends JavTestCase
{
    public function testCreatMoviePost()
    {
        $wordPressService = app(WordPressService::class);
        /**
         * @var Movie $movie
         */
        $movie = Movie::factory()->create();
        $movie->wordpress()->create([
            'title' => $this->faker->text,
            'state_code' => State::STATE_COMPLETED,
        ]);

        $this->assertNull($wordPressService->createMoviePost($movie));
    }

    public function testSend()
    {
        $movie = Movie::factory()->create();
        $wordPressService = app(WordPressService::class);
        $wordPressPost = $wordPressService->createMoviePost($movie);

        $wordPressService->send($wordPressPost);

        Mail::assertSent(WordPressPostEmail::class);
        $wordPressPost->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $wordPressPost->state_code);
    }
}
