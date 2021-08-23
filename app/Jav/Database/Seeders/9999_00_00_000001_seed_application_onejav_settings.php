<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class SeedApplicationOnejavSettings extends Migration
{
    protected string $table = 'applications';

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }

        $this->seedSettings([
            'onejav' => [
                'total_pages' => 7800,
            ],
        ]);
    }

    protected function seedSettings(array $settingsNew)
    {
        foreach ($settingsNew as $name => $settingNew) {
            $settings = [];
            $application = DB::table($this->table)
                ->where([
                    'name' => $name,
                ])
                ->first()
            ;

            if ($application) {
                $settings = $application['settings'];
            }

            foreach ($settingNew as $key => $value) {
                $settings[$key] = $value;
            }

            DB::table($this->table)->updateOrInsert(
                [
                    'name' => $name,
                ],
                [
                    'settings' => json_encode($settings),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
