<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;


class Action extends AppModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "actions";

    protected $guard = ['action_id'];

    protected $fillable = ['name', 'value', 'is_default'];

    protected $dates = ['created_at', 'modified_at'];


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->action_id = Uuid::generate()->string;
        });
    }

    public static function getActions()
    {
        return self::where(['is_active' => 1, 'deleted_at' => null]);
    }

    public static function getAction($action_id)
    {
        return self::where(['action_id' => $action_id, 'is_active' => 1, 'deleted_at' => null])->first();
    }

    public static function getActionList($per_page)
    {
        return self::where(['is_active' => 1, 'deleted_at' => null])->orderby('created_at', 'desc')->paginate($per_page);
    }

    public static function createAction($form)
    {
        return self::create($form);
    }

    public static function getDefaultAction()
    {
        return self::where(['is_default' => 1, 'deleted_at' => null]);
    }
}
