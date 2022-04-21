<?php

use App\Jav\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jav_states', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->string('entity')->index();
            $table->string('state');

            $table->timestamps();
            $table->softDeletes();
        });

        $now = Carbon::now();

        foreach (State::STATES as $referenceCode => $state) {
            DB::table('jav_states')->updateOrInsert(
                [
                    'reference_code' => $referenceCode,
                ],
                array_merge(['state' => $state, 'entity' => 'core'], ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jav_states');
    }
};
