<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BoardRequest;

class CreateBoardRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default(BoardRequest::STATUS_PENDING);
            $table->unsignedBigInteger('invite_id')->nullable();
            $table->timestamps();

            $table->unique(['board_id', 'user_id']);

            $table->foreign('board_id')
                ->references('id')
                ->on('boards')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('invite_id')
                ->references('id')
                ->on('invites')
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
        Schema::dropIfExists('board_requests');
    }
}
