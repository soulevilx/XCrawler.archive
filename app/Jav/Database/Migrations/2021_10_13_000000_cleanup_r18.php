<?php

use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Models\R18;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class CleanupR18 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        foreach (DB::table('r18')->cursor() as $item) {
            $url = trim($item->url, '/');
            $r18Item = R18::where('url', $url)
                ->where('id', '<>', $item->id)
                ->first();

            if (!$r18Item) {
                continue;
            }

            ItemFetch::dispatch($r18Item)->onQueue('crawling');
            DB::table('r18')->where('id', $item->id)->update([
                'deleted_at' => $now
            ]);
        }
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
}
