<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;


class Prize extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffle_prizes";

    protected $guard = ['raffle_prize_id', 'raffle_id'];

    protected $fillable = ['name', 'amount', 'order', 'image', 'is_active'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->raffle_prize_id = Uuid::generate()->string;
        });
    }

    public static function getPrizes($per_page, $search = false)
    {
        if ($search) {
            $object = self::where(['is_active' => 1, 'deleted_at' => null])->orderby('id', 'desc')
                ->where("name", "LIKE", "%{$search}%")
                ->paginate($per_page);
        } else {
            $object = self::where(['is_active' => 1, 'deleted_at' => null])->orderby('id', 'desc')->paginate($per_page);
        }

        return $object;
    }

    public static function getPrize($raffle_prize_id)
    {
        return self::where(['raffle_prize_id' => $raffle_prize_id, 'is_active' => 1, 'deleted_at' => null])->first();
    }

    public static function getPrizesList()
    {
        return self::where(['is_active' => 1, 'deleted_at' => null])->orderby('name', 'asc')->get();
    }

    public static function createPrize($form)
    {
        return self::create($form);
    }

    public static function checkDuplicate($prize_name)
    {
        return self::where(['name' => $prize_name, 'is_active' => 1, 'deleted_at' => null])->count() ? false : true;
    }
}
