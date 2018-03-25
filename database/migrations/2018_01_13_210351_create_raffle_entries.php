<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaffleEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('raffle_entry_id')->unique();
            $table->uuid('raffle_id');
            $table->string('email', 100);
            $table->string('code', 10);
            $table->tinyInteger('is_winner')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['email', 'code', 'is_active']);
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
