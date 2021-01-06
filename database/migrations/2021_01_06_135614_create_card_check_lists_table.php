<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CardCheckList;

class CreateCardCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_check_lists', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('status')->default(CardCheckList::STATUS_INCOMPLETE);
            $table->foreignId('card_id')->constrained('cards');
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
        Schema::dropIfExists('card_check_lists');
    }
}
