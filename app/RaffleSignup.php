<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use DB;


class RaffleSignup extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffle_signups";

    protected $guard = ['raffle_id', 'raffle_signup_id'];

    protected $fillable = ['raffle_id', 'email', 'code'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->raffle_signup_id = Uuid::generate()->string;
        });
    }

    public static function createSignup($form)
    {
        return self::create($form);
    }

    public static function checkRaffleEntry($raffle_id, $email_address)
    {
        $found = self::where(['raffle_id' => $raffle_id, 'email' => $email_address, 'is_active' => 1, 'deleted_at' => null]);

        return $found->count() ? false : true;
    }

    public static function getSummary($raffle_id)
    {
        $summary = null;

        // get number of signups per raffle
        $signups = self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null]);

        $summary['total_signups'] = $signups->count();

        // get total number of entries
        if ($signups->count()) {
            $total_entries = 0;
            foreach ($signups->get() as $signup) {
                $entries = DB::table('raffle_entries')->where(['raffle_signup_id' => $signup->raffle_signup_id, 'is_active' => 1, 'deleted_at' => null]);

                $total_entries += $entries->count();
            }

            $summary['total_entries'] = $total_entries;
        } else {
            $summary['total_entries'] = 0;
        }

        return $summary;
    }

    public static function validateCode($code)
    {
        return self::where(['code' => $code, 'is_active' => 1, 'deleted_at' => null])->count() ? true : false;
    }

    public static function getSignupDetails($raffle_id, $code)
    {
        return self::where(['raffle_id' => $raffle_id, 'code' => $code, 'is_active' => 1, 'deleted_at' => null])->first();
    }

    public static function getTotalSignups()
    {
        return self::where(['deleted_at' => null])->count();
    }

    public static function getSignupByRaffleEntryId($raffle_signup_id, $extras = null)
    {
        $default = ['raffle_signup_id' => $raffle_signup_id, 'is_active' => 1, 'deleted_at' => null];

        if (!is_null($extras)) {
            $conditions = array_merge($default, $extras);
        } else {
            $conditions = $default;
        }

        return self::where($conditions)->first();
    }
}
