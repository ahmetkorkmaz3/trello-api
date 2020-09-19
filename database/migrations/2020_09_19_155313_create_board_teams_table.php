<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('team_id');

            $table->foreign('board_id')
                ->references('id')
                ->on('boards')
                ->onDelete('cascade');

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_teams');
    }
}
