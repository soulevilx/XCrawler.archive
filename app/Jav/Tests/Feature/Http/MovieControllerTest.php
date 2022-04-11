<?php

namespace App\Jav\Tests\Feature\Http;

use App\Core\Services\Facades\Application;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;
use App\Jav\Models\Onejav;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    public function testPostToWordPress()
    {
        Mail::fake();

        Application::setSetting('jav', 'enable_post_to_wordpress', false);
        $onejav = Onejav::factory()->create();

        // Redirect
        $this->post('/jav/movies/' . $onejav->movie->dvd_id . '/to-wordpress')->assertStatus(302);

        Mail::assertSent(WordPressPostEmail::class, function ($mail) use ($onejav) {
            return $mail->movie->is($onejav->movie);
        });
    }
}
