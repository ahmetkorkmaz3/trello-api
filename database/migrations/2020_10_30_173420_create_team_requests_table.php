<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TeamRequest;

class CreateTeamRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default(TeamRequest::STATUS_PENDING);
            $table->unsignedBigInteger('invite_id')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
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
        Schema::dropIfExists('team_requests');
    }
}
