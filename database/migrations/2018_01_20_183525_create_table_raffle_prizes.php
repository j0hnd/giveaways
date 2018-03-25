<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRafflePrizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffle_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('raffle_prize_id');
            $table->uuid('raffle_id');
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->float('amount');
            $table->integer('order');
            $table->string('image_path', 100)->nullable();
            $table->tinyInteger('is_active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['raffle_prize_id', 'name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raffle_prizes');
    }
}
