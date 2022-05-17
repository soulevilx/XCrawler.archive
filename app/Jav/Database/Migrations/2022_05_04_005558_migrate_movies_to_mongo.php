<?php

use App\Jav\Models\Movie;
use App\Jav\Services\Movie\MovieService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateMoviesToMongo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $service = app(MovieService::class);
        foreach (Movie::cursor() as $movie) {
            $service->createIndex($movie);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mongo', function (Blueprint $table) {
            //
        });
    }
}
