<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRaffleActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('raffle_action_id');
            $table->uuid('raffle_id');
            $table->enum('source', ['web', 'facebook share', 'facebook likes', 'twitter', 'instagram', 'others']);
            $table->text('description')->nullable();
            $table->tinyInteger('is_active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['raffle_action_id', 'source', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raffle_actions');
    }
}
