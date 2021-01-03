<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BoardUserInvite;

class CreateBoardUserInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_user_invites', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('board_id')->constrained('boards');
            $table->string('status')->default(BoardUserInvite::STATUS_PENDING);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_user_invites');
    }
}
