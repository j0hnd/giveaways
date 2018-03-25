<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaffles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('raffle_id')->unique();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('closed_date')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['name', 'is_active']);
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raffles');
    }
}
