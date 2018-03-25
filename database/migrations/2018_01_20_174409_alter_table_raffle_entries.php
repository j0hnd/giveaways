<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRaffleEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_entries', function (Blueprint $table) {
            Schema::dropIfExists('raffle_entries');

            $table->increments('id');
            $table->uuid('raffle_entry_id')->unique();
            $table->uuid('raffle_signup_id');
            $table->uuid('action_id');
            $table->tinyInteger('is_winner')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['raffle_signup_id', 'action_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raffle_entries');
    }
}
