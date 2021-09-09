<?php

namespace App\Jav\Console\Commands;

use App\Core\Models\State;
use App\Jav\Services\WordPressPostService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Core\Models\WordPressPost as WordPressPostModel;
use App\Jav\Mail\WordPressPost as WordPressPostEmail;

class WordPressPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:email-wordpress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sendmail to WordPress';

    public function handle(WordPressPostService $service)
    {
        $service->send();
    }
}
