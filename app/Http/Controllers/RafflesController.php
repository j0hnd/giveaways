<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use App\Action;
use App\RaffleEntry;
use App\RaffleSignup;
use App\RaffleWinner;
use App\Configurations;
use App\Raffle;
use App\RaffleAction;
use App\Prize;
use App\Http\Requests\FormRafflesRequest;
use DB;


class RafflesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raffles     = Raffle::getRaffles($this->per_page);
        $prize_list  = Prize::getPrizesList();
        $action_list = Action::getActionList($this->per_page);
        $stats       = [
            'total_number_of_raffles'     => Raffle::getCount(),
            'total_number_of_signups'     => RaffleSignup::getTotalSignups(),
            'total_number_of_fb_share'    => RaffleEntry::getTotalFBShare(),
            'total_number_of_prize_given' => RaffleWinner::getTotalPrizeGiven()
        ];

        // get configuration for auto draw
        $config = Configurations::getConfig('auto_draw');

        if ($config->config_value == "false") {
            $toggle_auto_draw = '';
        } else {
            $toggle_auto_draw = 'checked';
        }

        return view('Raffles.index', compact('raffles', 'prize_list', 'action_list', 'stats', 'toggle_auto_draw'));
    }

    public function winners()
    {
        $winners     = Raffle::getRaffleWinners($this->per_page);
        $prize_list  = Prize::getPrizesList();
        $action_list = Action::getActionList($this->per_page);

        return view('Raffles.winners', compact('winners', 'prize_list' , 'action_list'));
    }

    public function create(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $form = view('Partials.Forms._raffle')->render();

                $response = ['success' => true, 'form' => $form];
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function store(FormRafflesRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $form = $request->all();

                if ($request->isMethod('post')) {

                    // generate slug
                    $slug = str_slug($form['name'], '-');

                    $start_time = !is_null($form['start_time']) ? date('H:i', strtotime($form['start_time'])) : "00:00:00";
                    $end_time   = !is_null($form['end_date']) ? date('H:i', strtotime($form['end_time'])) : "00:00:00";

                    $start_date = !is_null($form['start_date']) ? date('Y-m-d H:i:00', strtotime($form['start_date']." ".$start_time)) : null;
                    $end_date   = !is_null($form['end_date']) ? date('Y-m-d H:i:00', strtotime($form['end_date']." ".$end_time)) : null;

                    if (strtotime($start_time) > strtotime($end_date)) {
                        $response['message'] = "You can't set a raffle date on a past date";
                        return response()->json($response);
                    }

                    DB::beginTransaction();

                    $id = Raffle::saveRaffle([
                        'name'              => $form['name'],
                        'slug'              => $slug,
                        'description'       => $form['description'],
                        'mechanics'         => $form['mechanics'],
                        'subtitle'          => $form['subtitle'],
                        'number_of_winners' => $form['number_of_winners'],
                        'start_date'        => $start_date,
                        'end_date'          => $end_date,
                    ]);

                    if ($id) {
                        // configure default actions for the created raffle
                        $actions_obj = Action::getDefaultAction();

                        if ($actions_obj->count()) {
                            foreach ($actions_obj->get() as $action) {
                                RaffleAction::saveRaffleAction([
                                    'raffle_id' => $id->raffle_id,
                                    'action_id' => $action->action_id,
                                    'is_active' => 1
                                ]);
                            }

                            DB::commit();

                            $response = ['success' => true, 'message' => 'Raffle created!'];
                        } else {
                            DB::rollback();
                        }
                    } else {
                        DB::rollback();
                    }
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function edit(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $raffle_id = $request->get('id');

                $raffle = Raffle::getRaffleInfo($raffle_id);

                if (count($raffle)) {
                    $form = view('Partials.Forms._raffle_edit', compact('raffle'))->render();
                } else {
                    $form = view('Partials.Forms._raffle')->render();
                }

                $response = ['success' => true, 'form' => $form];
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function updates(FormRafflesRequest $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    $form = $request->all();

                    $raffle_info = Raffle::where(['raffle_id' => $form['id'], 'is_active' => 1]);

                    if ($raffle_info->count()) {
                        // regenerate raffle url
                        $slug = str_replace(' ', '-', strtolower($form['name']));

                        $start_time = !is_null($form['start_time']) ? date('H:i', strtotime($form['start_time'])) : "00:00:00";
                        $end_time   = !is_null($form['end_date']) ? date('H:i', strtotime($form['end_time'])) : "00:00:00";

                        $start_date = !is_null($form['start_date']) ? date('Y-m-d H:i:00', strtotime($form['start_date']." ".$start_time)) : null;
                        $end_date   = !is_null($form['end_date']) ? date('Y-m-d H:i:00', strtotime($form['end_date']." ".$end_time)) : null;

                        $raffle = $raffle_info->first();
                        $raffle->name              = $form['name'];
                        $raffle->slug              = $slug;
                        $raffle->subtitle          = $form['subtitle'];
                        $raffle->description       = $form['description'];
                        $raffle->mechanics         = $form['mechanics'];
                        $raffle->number_of_winners = $form['number_of_winners'];
                        $raffle->start_date        = $start_date;
                        $raffle->end_date          = $end_date;

                        if ($raffle->save()) {
                            $response = ['success' => true, 'message' => 'Raffle updated!'];
                        }
                    }
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function destroy(FormRafflesRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('delete')) {

                    $form = $request->all();

                    $raffle_info = Raffle::where(['raffle_id' => $form['id']]);

                    if ($raffle_info->count()) {
                        $raffle_info->delete();

                        $response = ['success' => true, 'message' => 'Raffle deleted!'];
                    }

                }

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function archive(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {

                    if (Raffle::archiveRaffle($request->get('id'))) {
                        $response = ['success' => true, 'message' => 'Raffle has been successfully archived'];
                    } else {
                        $response['message'] = "Error in archiving raffle.";
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function closed(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {

                    if (Raffle::closeRaffle($request->get('id'))) {
                        $response = ['success' => true, 'message' => 'Raffle has been successfully closed'];
                    } else {
                        $response['message'] = "Error in closing this raffle.";
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function search(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {
                    $needle = $request->get('search');

                    $raffles = Raffle::getRaffles($this->per_page, $needle);

                    $list = view('Partials.Raffles._list', compact('raffles'))->render();

                    $response = ['success' => true, 'data' => ['list' => $list]];
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }


    /**
     * Reload list of raffles
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function reload(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $raffles = Raffle::getRaffles($this->per_page);

                $list = view('Partials.Raffles._list', compact('raffles'))->render();

                $response = ['success' => true, 'data' => ['list' => $list]];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function updateAutoDraw(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('put')) {
                    $form = $request->all();

                    if (Configurations::setConfig('auto_draw', ['config_value' => $form['auto_draw']])) {
                        $message = $form['auto_draw'] ? "Auto draw has been enabled" : "Auto draw has been enabled";

                        $response = ['success' => true, 'message' => $message];
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }
}
