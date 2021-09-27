<?php

namespace App\Jav\Tests\Feature\Console;

use App\Core\Models\State;
use App\Jav\Mail\WordPressPost;
use App\Jav\Models\Movie;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Mail;


class WordPressPostTest extends JavTestCase
{
    public function testSendmailNothing()
    {
        $this->artisan('jav:email-wordpress');

        Mail::assertNothingSent();
    }

    public function testSendmail()
    {
        $movie = Movie::factory()->create();
        $movie->wordpress()->create([
            'title' => $movie->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        $this->artisan('jav:email-wordpress');

        Mail::assertSent(WordPressPost::class, function ($mail) use ($movie) {
            return $mail->movie->is($movie);
        });
        $this->assertEquals(State::STATE_COMPLETED, $movie->wordpress->refresh()->state_code);
    }

    public function testSendmailWithProvidedDvdId()
    {
        $movie = Movie::factory()->create();
        $movie->wordpress()->create([
            'title' => $movie->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        $movie2 = Movie::factory()->create();
        $movie2->wordpress()->create([
            'title' => $movie2->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        $this->artisan('jav:email-wordpress --dvdid=' . $movie2->dvd_id);
        Mail::assertSent(WordPressPost::class, function ($mail) use ($movie2) {
            return $mail->movie->is($movie2);
        });

        $this->assertEquals(State::STATE_COMPLETED, $movie2->wordpress->refresh()->state_code);
        $this->assertEquals(State::STATE_INIT, $movie->wordpress->refresh()->state_code);
    }
}
