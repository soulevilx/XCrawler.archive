<?php

use App\Core\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class SeedCoreStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        foreach (State::STATES as $referenceCode => $state) {
            DB::table('states')->updateOrInsert(
                [
                    'reference_code' => $referenceCode,
                ],
                array_merge(['state' => $state, 'entity' => 'core'], ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}
