<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Webpatser\Uuid\Uuid;

use DB;
use DateTime;


class Raffle extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffles";

    protected $guard = ['raffle_id'];

    protected $fillable = ['name', 'slug', 'subtitle', 'description', 'mechanics', 'number_of_winners', 'start_date', 'end_date'];

    protected $dates = ['start_date', 'end_date', 'deleted_at', 'drawn_date', 'created_at', 'modified_at'];



    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->raffle_id = Uuid::generate()->string;
        });
    }

    public static function getRaffles($raffle_per_page, $search = false)
    {
        $raffles = null;
        $results = null;

        try {

            if ($search) {
                $object = self::where([ 'is_active' => 1, 'deleted_at' => null ])
                    ->where("name", "LIKE", "%{$search}%")
                    ->orWhere("subtitle", "LIKE", "%{$search}%")
                    ->orWhere("description", "LIKE", "%{$search}%")
                    ->select(DB::raw("raffle_id, name, slug, description, subtitle, number_of_winners, start_date, end_date, closed_date"))
                    ->orderBy(DB::raw("IF(closed_date IS NOT NULL, 999999, IF(NOW() BETWEEN start_date AND end_date, '1', IF(NOW() > end_date, '2', 888888)))"))
                    ->paginate($raffle_per_page);
            } else {
                $object = self::where([ 'is_active' => 1, 'deleted_at' => null ])
                    ->select(DB::raw("raffle_id, name, slug, description, subtitle, number_of_winners, start_date, end_date, closed_date"))
                    ->orderBy(DB::raw("IF(closed_date IS NOT NULL, 999999, IF(NOW() BETWEEN start_date AND end_date, '1', IF(NOW() > end_date, '2', 888888)))"))
                    ->paginate($raffle_per_page);
            }


            if ($object->count()) {
                foreach ($object as $i => $obj) {
                    $start_date_obj = new DateTime($obj->start_date);
                    $start_date = $start_date_obj->format('m/d/Y');
                    $start_time = $start_date_obj->format('h:i A');

                    $end_date_obj = new DateTime($obj->end_date);
                    $end_date = $end_date_obj->format('m/d/Y');
                    $end_time = $end_date_obj->format('h:i A');


                    $raffles[$i]['raffle_id']         = $obj->raffle_id;
                    $raffles[$i]['raffle_name']       = $obj->name;
                    $raffles[$i]['raffle_url']        = URL::to('/')."/".$obj->slug;
                    $raffles[$i]['description']       = $obj->description;
                    $raffles[$i]['mechanics']         = $obj->mechanics;
                    $raffles[$i]['subtitle']          = $obj->subtitle;
                    $raffles[$i]['number_of_winners'] = $obj->number_of_winners;
                    $raffles[$i]['start_date']        = $start_date;
                    $raffles[$i]['start_time']        = $start_time;
                    $raffles[$i]['end_date']          = $end_date;
                    $raffles[$i]['end_time']          = $end_time;
                    $raffles[$i]['closed_date']       = is_null($obj->closed_date) ? "" : date('Y-m-d H:i A', strtotime($obj->closed_date));
                    $raffles[$i]['summary']           = RaffleSignup::getSummary($obj->raffle_id);
                }

                $results = ['data' => $raffles, 'object' => $object];
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }

    public static function getRaffleWinners($entries_per_page)
    {
        $data = null;

        try {

            $winners = self::join('raffle_signups', 'raffle_signups.raffle_id', '=', 'raffles.raffle_id')
                ->join('raffle_entries', 'raffle_entries.raffle_signup_id', '=', 'raffle_signups.raffle_signup_id')
                ->join('raffle_winners', 'raffle_winners.raffle_entry_id', '=', 'raffle_entries.raffle_entry_id')
                ->join('raffle_prizes', 'raffle_prizes.raffle_prize_id', '=', 'raffle_winners.raffle_prize_id')
                ->where([
                    'raffles.is_active'        => 1,
                    'raffle_signups.is_active' => 1,
                    'raffle_entries.is_active' => 1,
                    'raffle_entries.is_winner' => 1
                ])
                ->whereNotNull('raffles.closed_date')
                ->select(DB::raw('raffles.raffle_id, raffles.name, raffle_signups.email, raffle_signups.code, raffles.closed_date, raffle_entries.position, raffle_prizes.name AS prize_name'))
                ->paginate($entries_per_page);

            if ($winners->count()) {
                $data = $winners;
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $data;
    }

    public static function saveRaffle($form)
    {
        return self::create($form);
    }

    public static function archiveRaffle($raffle_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null])->update(['is_active' => 0]);
    }

    public static function closeRaffle($raffle_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null])->update(['closed_date' => date('Y-m-d H:i:s')]);
    }

    public static function getRaffleList()
    {
        return self::where(['is_active' => 1, 'deleted_at' => null, 'closed_date' => null])->get();
    }

    public static function getRaffleInfo($raffle_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null, 'closed_date' => null])->first();
    }

    public static function getRaffleBySlug($slug)
    {
        return self::where(['slug' => $slug, 'is_active' => 1, 'deleted_at' => null, 'closed_date' => null])->first();
    }

    public static function isRaffleIdValid($raffle_id)
    {
        return self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null, 'closed_date' => null])->count() ? true : false;
    }

    public static function isRaffleValid($raffle_name)
    {
        return self::whereRaw("(NOW() >= start_date AND NOW() <= end_date)")
                ->where([
                    'slug'        => $raffle_name,
                    'is_active'   => 1,
                    'deleted_at'  => null,
                    'closed_date' => null
                ])
                ->count() ? true : false;
    }

    public static function getPrimaryId($raffle_id)
    {
        return self::where('raffle_id', $raffle_id)->first()->id;
    }

    public static function getCount()
    {
        return self::where(['deleted_at' => null])->count();
    }

    public static function getRafflesForDraw($timestamp)
    {
        return self::select('raffle_id', 'name', 'end_date')
            ->where(['is_active' => 1, 'deleted_at' => null, 'closed_date' => null, 'drawn_date' => null, 'end_date' => $timestamp]);
    }

    public static function updateRaffle($raffle_id, $data = [])
    {
        if (!is_array($data)) {
            return null;
        }

        return self::where(['raffle_id' => $raffle_id, 'is_active' => 1, 'deleted_at' => null])->update($data);
    }
}
