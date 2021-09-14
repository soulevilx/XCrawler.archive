<?php

namespace App\Jav\Console\Commands;

use App\Core\Models\State;
use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use App\Jav\Models\Performer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Migrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate from previous version';

    public function handle()
    {
        $this->migrateMovies();
        $this->migrateIdols();
        $this->migrateGenres();
        $this->migrateIdolMovie();
        $this->migrateTagMovie();
        $this->migrateWordPressPost();
        $this->migrateOnejav();
        $this->migrateR18();
        $this->migrateXCitIdol();
        $this->migrateXCityVideos();
    }

    public function migrateMovies()
    {
        $this->output->title('Migrate Movies');

        $data = $this->loadData('movies');
        $this->output->progressStart(count($data));

        foreach ($data as $movie) {
            if ($this->isExists('movies', ['id' => $movie['id']])) {
                $this->output->progressAdvance();
                continue;
            }

            if (!empty($movie['channel'])) {
                $movie['channel'] = explode(',', $movie['channel']);
            }

            if (!isset($movie['content_id']) || (!$movie['content_id'] && $movie['dvd_id'])) {
                $movie['content_id'] = $movie['dvd_id'];
            }

            if (!isset($movie['dvd_id']) || (!$movie['dvd_id'] && $movie['content_id'])) {
                $movie['dvd_id'] = $movie['content_id'];
            }

            DB::table('movies')
                ->insert([
                    'id' => $movie['id'],
                    'name' => $movie['name'],
                    'cover' => $movie['cover'],
                    'sales_date' => $movie['sales_date'],
                    'release_date' => $movie['release_date'],
                    'content_id' => $movie['content_id'],
                    'dvd_id' => $movie['dvd_id'],
                    'description' => $movie['description'],
                    'time' => $movie['time'],
                    'director' => $movie['director'],
                    'studio' => $movie['studio'],
                    'label' => $movie['label'],
                    'channels' => json_encode($movie['channel']),
                    'series' => $movie['series'],
                    'gallery' => $movie['gallery'],
                    'sample' => json_encode($movie['sample']),
                    'created_at' => $movie['created_at'],
                    'updated_at' => $movie['updated_at'],
                    'deleted_at' => $movie['deleted_at'],
                ]);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateIdols()
    {
        $this->output->title('Migrate Performers');

        $data = $this->loadData('idols');
        $this->output->progressStart(count($data));

        foreach ($data as $idol) {
            if (DB::table('performers')->where('id', $idol['id'])->exists()) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('performers')
                ->insert([
                    'id' => $idol['id'],
                    'name' => $idol['name'],
                    'alias' => $idol['alias'],
                    'birthday' => $idol['birthday'],
                    'blood_type' => $idol['blood_type'],
                    'city' => $idol['city'],
                    'height' => $idol['height'],
                    'breast' => $idol['breast'],
                    'waist' => $idol['waist'],
                    'hips' => $idol['hips'],
                    'cover' => $idol['cover'],
                    'favorite' => $idol['favorite'],
                    'created_at' => $idol['created_at'],
                    'updated_at' => $idol['updated_at'],
                    'deleted_at' => $idol['deleted_at'],
                ]);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateGenres()
    {
        $this->output->title('Migrate Genres');

        $data = $this->loadData('tags');
        $this->output->progressStart(count($data));

        foreach ($data as $genre) {
            if (DB::table('genres')->where('id', $genre['id'])->exists()) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('genres')
                ->insert([
                    'id' => $genre['id'],
                    'name' => $genre['name'],
                    'created_at' => $genre['created_at'],
                    'updated_at' => $genre['updated_at'],
                    'deleted_at' => $genre['deleted_at'],
                ]);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateIdolMovie()
    {
        $this->output->title('Migrate Performers - Movies');

        $data = $this->loadData('idol_movie');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if (!DB::table('performers')->where('id', $row['idol_id'])->exists()) {
                $this->output->error('Invalid idol');
                continue;
            }
            if (!DB::table('movies')->where('id', $row['movie_id'])->exists()) {
                $this->output->error('Invalid movie');
                continue;
            }

            if (DB::table('movie_performers')->where([
                'id' => $row['id'],
                'performer_id' => $row['idol_id'],
                'movie_id' => $row['movie_id'],
            ])->exists()) {
                continue;
            }

            DB::table('movie_performers')->insert([
                'id' => $row['id'],
                'performer_id' => $row['idol_id'],
                'movie_id' => $row['movie_id'],
                'created_at' => $row['created_at'] ?? Carbon::now(),
                'updated_at' => $row['updated_at'] ?? Carbon::now(),
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateTagMovie()
    {
        $this->output->title('Migrate Genres - Movies');

        $data = $this->loadData('tag_movie');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if (!$this->isExists('genres', ['id' => $row['tag_id']])) {
                $this->output->error('Invalid genre');
                continue;
            }

            if (!$this->isExists('movies', ['id' => $row['movie_id']])) {
                $this->output->error('Invalid movie');
                continue;
            }

            if (DB::table('movie_genres')->where([
                'id' => $row['id'],
                'genre_id' => $row['tag_id'],
                'movie_id' => $row['movie_id'],
            ])->exists()) {
                continue;
            }

            DB::table('movie_genres')->insert([
                'id' => $row['id'],
                'genre_id' => $row['tag_id'],
                'movie_id' => $row['movie_id'],
                'created_at' => $row['created_at'] ?? Carbon::now(),
                'updated_at' => $row['updated_at'] ?? Carbon::now(),
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateWordPressPost()
    {
        $this->output->title('Migrate WordPress Posts');

        $data = $this->loadData('wordpress_posts');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            $movie = DB::table('movies')
                ->where('dvd_id', $row['title'])
                ->first();

            // Idol case
            if (!$movie) {
                $idol = DB::table('performers')
                    ->where('name', $row['title'])
                    ->first();

                if ($this->isExists('wordpress_posts', [
                    'model_id' => $idol->id,
                    'model_type' => Performer::class,
                    'title' => $row['title']
                ])) {
                    $this->output->progressAdvance();
                    continue;
                }

                DB::table('wordpress_posts')
                    ->insert([
                        'model_id' => $idol->id,
                        'model_type' => Performer::class,
                        'title' => $row['title'],
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at'],
                        'deleted_at' => $row['deleted_at'] ?? null,
                        'state_code' => State::STATE_COMPLETED
                    ]);

                $this->output->progressAdvance();

                continue;
            }

            // Movie
            if ($this->isExists('wordpress_posts', [
                'model_id' => $movie->id,
                'model_type' => Movie::class,
                'title' => $row['title']
            ])) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('wordpress_posts')
                ->insert([
                    'model_id' => $movie->id,
                    'model_type' => Movie::class,
                    'title' => $row['title'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'deleted_at' => $row['deleted_at'] ?? null,
                    'state_code' => State::STATE_COMPLETED
                ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateOnejav()
    {
        $this->output->title('Migrate Onejav');

        $data = $this->loadData('onejav');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if ($this->isExists('onejav', ['dvd_id' => $row['dvd_id']])) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('onejav')->insert([
                'id' => $row['id'],
                'url' => str_replace(Onejav::BASE_URL, '', $row['url']),
                'cover' => $row['cover'],
                'dvd_id' => $row['dvd_id'],
                'size' => $row['size'],
                'date' => $row['date'],
                'genres' => $row['tags'],
                'description' => $row['description'],
                'performers' => $row['actresses'],
                'torrent' => $row['torrent'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'deleted_at' => $row['deleted_at'],
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateR18()
    {
        $this->output->title('Migrate R18');

        $data = $this->loadData('r18');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if ($this->isExists('r18', ['content_id' => $row['content_id']])) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('r18')->insert([
                'id' => $row['id'],
                'url' => $row['url'],
                'cover' => $row['cover'],
                'title' => $row['title'],
                'release_date' => $row['release_date'],
                'runtime' => $row['runtime'],
                'director' => $row['director'],
                'studio' => $row['studio'],
                'label' => $row['label'],
                'genres' => $row['tags'],
                'performers' => $row['actresses'],
                'channels' => is_string($row['channel']) ? json_encode(explode(',', $row['channel'])) : null,
                'content_id' => $row['content_id'],
                'dvd_id' => $row['dvd_id'] ?? $row['content_id'],
                'series' => $row['series'],
                'languages' => $row['languages'],
                'sample' => is_string($row['sample']) ? json_encode([$row['sample']]) : null,
                'gallery' => $row['gallery'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'deleted_at' => $row['deleted_at'],
                'state_code' => State::STATE_COMPLETED
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateXCitIdol()
    {
        $this->output->title('Migrate XCity idols');

        $data = $this->loadData('x_city_idols');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if ($this->isExists('xcity_idols', ['name' => $row['name']])) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('xcity_idols')->insert([
                'id' => $row['id'],
                'url' => $row['url'],
                'name' => $row['name'],
                'cover' => $row['cover'],
                'favorite' => $row['favorite'],
                'birthday' => $row['birthday'],
                'blood_type' => $row['blood_type'],
                'city' => $row['city'],
                'height' => $row['height'],
                'breast' => $row['breast'],
                'waist' => $row['waist'],
                'hips' => $row['hips'],
                'state_code' => $row['state_code'] === 'XCIC' ? State::STATE_COMPLETED : State::STATE_INIT,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'deleted_at' => $row['deleted_at'],
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function migrateXCityVideos()
    {
        $this->output->title('Migrate XCity videos');

        $data = $this->loadData('x_city_videos');
        $this->output->progressStart(count($data));

        foreach ($data as $row) {
            if ($this->isExists('xcity_videos', ['dvd_id' => $row['dvd_id']])) {
                $this->output->progressAdvance();
                continue;
            }

            DB::table('xcity_videos')->insert([
                'id' => $row['id'],
                'url' => 'invalid_' . md5(serialize($row)),
                'name' => $row['name'],
                'cover' => $row['cover'],
                'sales_date' => $row['sales_date'],
                'release_date' => $row['release_date'],
                'item_number' => $row['item_number'],
                'dvd_id' => $row['dvd_id'],
                'actresses' => $row['actresses'],
                'description' => $row['description'],
                'running_time' => $row['time'],
                'director' => $row['director'],
                'marker' => $row['marker'],
                'studio' => $row['studio'],
                'label' => $row['label'],
                'channel' => $row['channel'],
                'series' => $row['series'],
                'gallery' => $row['gallery'],
                'sample' => $row['sample'],
                'favorite' => $row['favorite'],
                'state_code' => State::STATE_PENDING,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'deleted_at' => $row['deleted_at'],
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    private function loadData(string $fileName): array
    {
        $filePath = __DIR__ . '/../../../../storage/app/migrations/' . app()->environment() . '/' . $fileName . '.json';
        if (!file_exists($filePath)) {
            return [];
        }

        return json_decode(file_get_contents($filePath), true);
    }

    private function isExists(string $tableName, array $whereConditions): bool
    {
        return DB::table($tableName)
            ->where($whereConditions)
            ->exists();
    }
}
