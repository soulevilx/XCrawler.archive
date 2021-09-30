<?php

namespace App\Jav\Tests\Feature\Http;

use App\Core\Models\State;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use App\Jav\Models\Movie;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    public function testPostToWordPress()
    {
        Mail::fake();
        $movie = Movie::factory()->create();

        $this->get('/jav/movies/' . $movie->dvd_id . '/to-wordpress')
            ->assertStatus(200)
            ->assertJsonStructure([
                'model_id',
                'model_type',
                'title',
                'state_code',
            ]);

        Mail::assertSent(WordPressPostEmail::class, function ($mail) use ($movie) {
            return $mail->movie->is($movie);
        });
    }

    public function testNotPostToWordPressWhenAlreadyPosted()
    {
        Mail::fake();
        $movie = Movie::factory()->create();
        $movie->wordpress()->create([
            'title' => $movie->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        $this->get('/jav/movies/' . $movie->dvd_id . '/to-wordpress')
            ->assertStatus(204);
    }
}
