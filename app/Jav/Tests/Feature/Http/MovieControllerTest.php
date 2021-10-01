<?php

namespace App\Jav\Tests\Feature\Http;

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

        $this->post('/jav/movies/' . $movie->dvd_id . '/to-wordpress')->assertStatus(200);

        Mail::assertSent(WordPressPostEmail::class, function ($mail) use ($movie) {
            return $mail->movie->is($movie);
        });
    }
}
