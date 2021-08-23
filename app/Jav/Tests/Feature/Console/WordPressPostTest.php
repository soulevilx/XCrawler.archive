<?php

namespace App\Jav\Tests\Feature\Console;

use App\Core\Models\State;
use App\Jav\Mail\WordPressPost;
use App\Jav\Models\Movie;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Mail;

/**
 * @internal
 * @coversNothing
 */
class WordPressPostTest extends JavTestCase
{
    public function testSendmail()
    {
        $movie = Movie::factory()->create();
        $movie->wordpress()->create([
            'title' => $movie->dvd_id,
            'state_code' => State::STATE_INIT,
        ]);

        $this->artisan('jav:email-wordpress');

        Mail::assertSent(WordPressPost::class);
        $this->assertEquals(State::STATE_COMPLETED, $movie->wordpress->refresh()->state_code);
    }
}
