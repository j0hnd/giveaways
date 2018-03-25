<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use DB;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Foreach_;


class RaffleEntry extends AppModel
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "raffle_entries";

    protected $guard = ['raffle_signup_id', 'raffle_entry_id', 'raffle_action_id'];

    protected $fillable = ['raffle_signup_id', 'raffle_action_id', 'is_winner'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->raffle_entry_id = Uuid::generate()->string;
        });
    }

    public static function getRaffleEntries($raffle_id, $entries_per_page)
    {
        $data = null;

        try {

            $entries = self::select(DB::Raw('raffle_entries.raffle_entry_id, raffle_signups.email, raffle_signups.code, raffle_entries.created_at, actions.name AS action_name, raffle_entries.position, raffle_entries.is_winner'))
                        ->join('raffle_signups', 'raffle_signups.raffle_signup_id', '=', 'raffle_entries.raffle_signup_id')
                        ->join('raffle_actions', 'raffle_actions.raffle_action_id', '=', 'raffle_entries.raffle_action_id')
                        ->join('actions', 'actions.action_id', '=', 'raffle_actions.action_id')
                        ->where([
                            'raffle_signups.raffle_id'  => $raffle_id,
                            'raffle_entries.is_active'  => 1,
                            'raffle_entries.deleted_at' => null
                        ])
                        ->orderBy('raffle_entries.is_winner', 'desc')
                        ->orderBy('raffle_entries.position', 'asc')
                        ->orderBy('raffle_entries.created_at', 'desc')
                        ->paginate($entries_per_page);

            if ($entries->count()) {
                $data = $entries;
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $data;
    }

    public static function getRaffleEntry($id)
    {
        $object = self::where('id', $id);
        return $object->count() ? $object->first() : null;
    }

    public static function createEntry($form)
    {
        return self::create($form);
    }

    public static function getRawRaffleEntries($raffle_id)
    {
        $data = null;
        
        try {
            
            $entries = self::where([
                            'raffle_signups.raffle_id'   => $raffle_id,
                            'raffle_signups.is_active'   => 1,
                            'raffle_entries.is_active'  => 1,
                            'raffle_entries.deleted_at' => null
                        ])
                        ->join('raffle_signups', 'raffle_signups.raffle_signup_id', '=', 'raffle_entries.raffle_signup_id')
                        ->join('raffle_actions', 'raffle_actions.raffle_action_id', '=', 'raffle_entries.raffle_action_id')
                        ->select('raffle_signups.email', 'raffle_actions.raffle_id', 'raffle_actions.raffle_action_id', 'raffle_entries.raffle_entry_id');

            if ($entries->count()) {
                foreach ($entries->get() as $i => $entry) {
                    $data[$i]['raffle_id']        = $entry->raffle_id;
                    $data[$i]['raffle_entry_id']  = $entry->raffle_entry_id;
                    $data[$i]['raffle_action_id'] = $entry->raffle_action_id;
                    $data[$i]['email']            = $entry->email;
                }
            }
            
        } catch (\Exception $e) {
            throw $e;
        }

        return $data;
    }

    public static function draw($number_of_entries, $number_of_winners = 1)
    {
        $high    = $number_of_entries - 1;
        $entries = range( 0, $high);
        shuffle($entries);

        $winners = null;

        foreach ($entries as $i => $entry) {
            $winners[$i] = $entry;

            if (($i + 1) == $number_of_winners) {
                break;
            }
        }

        return $winners;
    }

    public static function getRaffleWinnerDetails($action_id)
    {
        $data = null;

        try {

            $info = self::select('raffles.id AS _rid', 'raffle_entries.id AS _eid')
                        ->join('raffle_signups', 'raffle_signups.raffle_signup_id', '=', 'raffle_entries.raffle_signup_id')
                        ->join('raffles', 'raffles.raffle_id', '=', 'raffle_signups.raffle_id')
                        ->join('raffle_actions', 'raffle_actions.raffle_action_id', '=', 'raffle_entries.raffle_action_id')
                        ->where([
                            'raffle_actions.action_id'  => $action_id,
                            'raffle_actions.is_active'  => 1,
                            'raffle_entries.is_active'  => 1,
                            'raffle_entries.deleted_at' => null
                        ]);

            if ($info->count()) {
                $data['rid']   = $info->first()->_rid;
                $data['eid']   = $info->first()->_eid;

                // update self
                $_self = self::find($info->first()->_eid);
                $_self->is_winner = 1;
                $_self->save();
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $data;
    }

    public static function getPrimaryId($raffle_entry_id)
    {
        return self::where('raffle_entry_id', $raffle_entry_id)->first()->id;
    }

    public static function validateEntry($raffle_signup_id, $raffle_action_id)
    {
        return self::where(['raffle_signup_id' => $raffle_signup_id, 'raffle_action_id' => $raffle_action_id, 'is_active' => 1, 'deleted_at' => null])->count() ? false : true;
    }

    public static function getTotalFBShare()
    {
        return self::where([
            'raffle_entries.deleted_at' => null,
            'actions.deleted_at'        => null,
            'actions.deleted_at'        => null,
            'actions.name'              => 'Share on Facebook'
        ])
            ->join('raffle_actions', 'raffle_actions.raffle_action_id', '=', 'raffle_entries.raffle_action_id')
            ->join('actions', 'actions.action_id', '=', 'raffle_actions.action_id')
            ->count();
    }

    public static function getDetailsByRaffleEntryId($raffle_entry_id, $extras = null)
    {
        $default = ['raffle_entry_id' => $raffle_entry_id, 'is_active' => 1, 'deleted_at' => null];

        if (!is_null($extras)) {
            $conditions = array_merge($default, $extras);
        } else {
            $conditions = $default;
        }

        return self::where($conditions)->first();
    }
}
