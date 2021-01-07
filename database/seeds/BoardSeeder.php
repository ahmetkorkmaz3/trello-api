<?php

use Illuminate\Database\Seeder;
use App\Models\Board;
use App\Models\Column;
use App\Models\Card;
use App\Models\User;
use App\Models\Team;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Board::class, 50)->create();
        factory(Column::class, 150)->create();
        factory(Card::class, 450)->create();

        $users = User::all();

        Board::all()->take(25)->each(function ($board) use ($users) {
            $board->users()->attach(
                $users->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

    }
}
