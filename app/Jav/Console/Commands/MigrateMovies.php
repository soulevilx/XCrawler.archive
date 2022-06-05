<?php

namespace App\Jav\Console\Commands;

use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use Illuminate\Console\Command;

class MigrateMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:migrate-movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Movies to MoviesIndex';

    public function handle()
    {
        $service = app(MovieService::class);
        foreach (Movie::cursor() as $movie) {
            $service->createIndex($movie);
        }
    }
}
