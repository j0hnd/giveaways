<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PrizeRoster extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "prize_rosters";

    protected $fillable = ['raffle_prize_id', 'raffle_id', 'order'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];


    public static function getRafflePrizes($raffle_id)
    {
        $data = self::where([
                        'prize_rosters.raffle_id'  => $raffle_id,
                        'prize_rosters.deleted_at' => null,
                        'raffle_prizes.deleted_at' => null
                    ])
                    ->join('raffle_prizes', 'raffle_prizes.raffle_prize_id', '=', 'prize_rosters.raffle_prize_id')
                    ->select('prize_rosters.id', 'raffle_prizes.name', 'raffle_prizes.amount', 'prize_rosters.order', 'raffle_prizes.raffle_prize_id', 'prize_rosters.raffle_id', 'raffle_prizes.image')
                    ->orderBy('prize_rosters.order', 'asc');

        return $data->get();
    }

    public static function getMainPrize($raffle_id)
    {
        $data = self::where([
            'prize_rosters.raffle_id'  => $raffle_id,
            'prize_rosters.deleted_at' => null,
            'raffle_prizes.deleted_at' => null,
            'prize_rosters.order'      => 1
        ])
            ->join('raffle_prizes', 'raffle_prizes.raffle_prize_id', '=', 'prize_rosters.raffle_prize_id')
            ->select('prize_rosters.id', 'raffle_prizes.name', 'raffle_prizes.amount', 'raffle_prizes.raffle_prize_id', 'prize_rosters.raffle_id', 'raffle_prizes.image')
            ->orderBy('prize_rosters.order', 'asc');

        return $data->first();
    }

    public static function getRafflePrizeId($raffle_id, $order)
    {
        return self::where(['raffle_id' => $raffle_id, 'order' => $order, 'deleted_at' => null])->first()->raffle_prize_id;
    }

    public static function createPrizeRoster($form)
    {
        return self::create($form);
    }

    public static function getRafflePrize($raffle_id, $raffle_prize_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'raffle_prize_id' => $raffle_prize_id, 'deleted_at' => null])->first();
    }

    public static function checkDuplicate($raffle_id, $raffle_prize_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'raffle_prize_id' => $raffle_prize_id, 'deleted_at' => null])->count() ? true : false;
    }

    public static function countRafflePrize($raffle_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'deleted_at' => null])->count();
    }
}
