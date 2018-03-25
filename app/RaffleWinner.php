<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RaffleWinner extends AppModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffle_winners";

    protected $guard = ['raffle_id', 'raffle_entry_id', 'raffle_prize_id'];

    protected $fillable = ['raffle_id', 'raffle_entry_id', 'raffle_prize_id'];

    protected $dates = ['created_at', 'modified_at'];


    public static function saveWinnerPrizes($form)
    {
        return self::create($form);
    }

    public static function getTotalPrizeGiven()
    {
        $total_amount = 0;

        $prizes = self::selectRaw("SUM(raffle_prizes.amount) AS total_amount")
            ->join('raffles', 'raffles.raffle_id', '=', 'raffle_winners.raffle_id')
            ->join('prize_rosters', 'prize_rosters.raffle_id', '=', 'raffles.raffle_id')
            ->join('raffle_prizes', 'raffle_prizes.raffle_prize_id', '=', 'prize_rosters.raffle_prize_id')
            ->first();

        if ($prizes->first()) {
            $total_amount = $prizes->total_amount;
        }

        return $total_amount;
    }
}
