<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Models\State;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use App\Jav\Services\WordPressPostService;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Mail;

class WordPressPostServiceTest extends JavTestCase
{
    public function testSend()
    {
        Mail::fake();

        $movie = Movie::factory()->create();
        $movieService = app(MovieService::class);
        $wordPressPost = $movieService->createWordPressPost($movie);

        $wordPressPostService = app(WordPressPostService::class);
        $wordPressPostService->send($wordPressPost);

        Mail::assertSent(WordPressPostEmail::class);
        $wordPressPost->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $wordPressPost->state_code);
    }

    public function testSendWithoutMovieProvide()
    {
        Mail::fake();

        $movie = Movie::factory()->create();
        $movieService = app(MovieService::class);
        $wordPressPost = $movieService->createWordPressPost($movie);

        $wordPressPostService = app(WordPressPostService::class);
        $wordPressPostService->send();

        Mail::assertSent(WordPressPostEmail::class);
        $wordPressPost->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $wordPressPost->state_code);
    }
}
