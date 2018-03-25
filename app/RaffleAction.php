<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class RaffleAction extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffle_actions";

    protected $guard = ['raffle_action_id'];

    protected $fillable = ['raffle_id', 'action_id', 'is_active'];

    protected $dates = ['created_at', 'modified_at'];


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->raffle_action_id = Uuid::generate()->string;
        });
    }

    public static function saveRaffleAction($form)
    {
        return self::create($form);
    }

    public static function getRaffleActions($raffle_id, $default_only = true, $except_default = false)
    {
        $data = null;

        try {
            $raffle_action_obj = self::select(DB::raw("raffle_actions.raffle_action_id, raffle_actions.raffle_id, actions.name"))
                ->join('raffles', 'raffles.raffle_id', '=', 'raffle_actions.raffle_id')
                ->join('actions', 'actions.action_id', '=', 'raffle_actions.action_id')
                ->where([
                    'raffle_actions.raffle_id' => $raffle_id,
                    'raffle_actions.is_active' => 1,
                    'raffle_actions.deleted_at' => null
                ]);

            if ($default_only and !$except_default) {
                $raffle_action_obj->where('actions.is_default', 1);
            }

            if (!$default_only and $except_default) {
                $raffle_action_obj->where('actions.is_default', 0);
            }

            if (!$default_only and !$except_default) {
                $raffle_action_obj->whereIn('actions.is_default', [0, 1]);
            }

            if ($raffle_action_obj->count()) {
                foreach ($raffle_action_obj->get() as $i => $item) {
                    $data[$i]['raffle_action_id'] = $item->raffle_action_id;
                    $data[$i]['raffle_id']        = $item->raffle_id;
                    $data[$i]['name']             = $item->name;
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $data;
    }

    public static function checkDuplicate($raffle_id, $action_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'action_id' => $action_id, 'deleted_at' => null])->count() ? true : false;
    }

    public static function getRaffleAction($raffle_id, $action_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'action_id' => $action_id, 'deleted_at' => null])->first();
    }

    public static function getRaffleActionData($raffle_action_id)
    {
        return self::where(['raffle_action_id' => $raffle_action_id, 'deleted_at' => null])->first();
    }

    public static function validateAction($raffle_id, $raffle_action_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'raffle_action_id' => $raffle_action_id, 'is_active' => 1, 'deleted_at' => null])->count() ? true : false;
    }

}
