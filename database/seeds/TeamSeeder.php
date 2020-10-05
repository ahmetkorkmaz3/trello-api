<?php

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Team::class, 50)->create();

        $users = User::all();

        Team::all()->each(function ($teams) use ($users) {
            $teams->users()->attach(
                $users->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}
