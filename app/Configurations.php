<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Configurations extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "configurations";

    protected $fillable = ['config_name', 'config_value', 'is_active'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];


    public static function getConfig($config_name)
    {
        return self::where(['config_name' => $config_name, 'is_active' => 1, 'deleted_at' => null])->first();
    }

    public static function setConfig($key, $data)
    {
        return self::where(['config_name' => $key])->update($data);
    }
}
