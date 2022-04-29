<?php

use App\Core\Services\Facades\Application;
use App\Jav\Services\SCuteService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Application::setSettings([
            'scute' => [
                'base_url' => SCuteService::BASE_URL,
                'total_pages' => SCuteService::DEFAULT_TOTAL_PAGES,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
