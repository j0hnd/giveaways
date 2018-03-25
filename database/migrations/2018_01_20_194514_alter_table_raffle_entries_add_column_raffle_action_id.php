<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRaffleEntriesAddColumnRaffleActionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raffle_entries', function($table) {
            $table->uuid('raffle_action_id')->after('raffle_signup_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raffle_entries', function($table) {
            $table->dropColumn('raffle_action_id');
        });
    }
}
