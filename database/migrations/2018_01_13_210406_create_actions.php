<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('action_id')->unique();
            $table->uuid('raffle_entry_id');
            $table->string('name', 100);
            $table->string('value', 100);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index(['raffle_entry_id', 'name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
