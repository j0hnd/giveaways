<?php

namespace App\Http\Controllers;

use App\PrizeRoster;
use App\Raffle;
use App\RaffleAction;
use App\RaffleSignup;
use App\RaffleEntry;
use App\Http\Requests\FormRaffleEntryRequest;
use App\Jobs\SendWelcomeEmail;
use App\Repositories\RaffleRepository;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use DateTime;



class RaffleEntriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['register', 'registration', 'signup_plus']]);
    }

    public function getRaffleEntries(Request $request, $raffle_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $entries = RaffleEntry::getRaffleEntries($raffle_id, $this->per_page);

                $list = view('Partials.RaffleEntries._entries', compact('entries'))->render();

                $response = ['success' => true, 'data' => ['list' => $list]];

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function registration(Request $request, $raffle)
    {
        try {
            // validate raffle's start and end date
            if (Raffle::isRaffleValid($raffle)) {
                // get raffle info
                $raffle_info = Raffle::getRaffleBySlug($raffle);

                // get default raffle action
                $default_raffle_action = RaffleAction::getRaffleActions($raffle_info->raffle_id, true);

                // get first prize of the raffle
                $prize = PrizeRoster::getMainPrize($raffle_info->raffle_id);

                // raffle url
                $raffle_url = URL::to('/')."/".$raffle_info->slug;

                if ($raffle_info) {
                    $form_action    = URL::to('/')."/r/".$raffle_info->slug.'/'.$raffle_info->raffle_id;

                    // calculate days remaining
                    $end_date       = new DateTime($raffle_info->end_date);
                    $current_date   = new DateTime('now');
                    $diff           = $current_date->diff($end_date)->format("%a");
                    $days_remaining = intval($diff);
                }
            } else {
                abort(404, "Raffle is invalid");
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return view('RaffleEntries.registration', compact('raffle_info', 'default_raffle_action', 'form_action', 'days_remaining', 'raffle_url', 'prize'));
    }

    public function register(FormRaffleEntryRequest $request, $raffle, $raffle_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {
                    $form = $request->all();

                    $code = str_random(10);

                    // get action id for "share on facebook"
                    $actions = RaffleAction::getRaffleActions($raffle_id, false, true);

                    if (!RaffleSignup::checkRaffleEntry($raffle_id, $form['email'])) {
                        $response['message'] = "Email address is already registered.";
                    }

                    // check if raffle id is valid
                    if (Raffle::isRaffleIdValid($raffle_id)) {

                        $signup_form = [
                            'raffle_id' => $raffle_id,
                            'email'     => $form['email'],
                            'code'      => $code,
                        ];

                        DB::beginTransaction();

                        if ($id = RaffleSignup::createSignup($signup_form)) {
                            $entry_form = [
                                'raffle_signup_id' => $id->raffle_signup_id,
                                'raffle_action_id' => $form['raffle_action_id'],
                                'is_active'        => 1
                            ];

                            if (RaffleEntry::createEntry($entry_form)) {
                                DB::commit();

                                $raffle_info = Raffle::getRaffleInfo($raffle_id);

                                $mail_data = [
                                    'recipient'   => $form['email'],
                                    'raffle_code' => $code,
                                    'raffle_name' => $raffle_info->name,
                                    'raffle_url'  => URL::to('/')."/".$raffle_info->slug . '/' . base64_encode($code),
                                    'end_date'    => $raffle_info->end_date
                                ];

                                // email raffle entry notification
                                $job = (new SendWelcomeEmail($mail_data))->delay(Carbon::now()->addMinute(2));
                                dispatch($job);

                                $response = ['success' => true, 'data' => ['raffle_id' => $raffle_id, 'code' => $code, 'actions' => $actions]];
                            } else {
                                DB::rollback();

                                $response['message'] = "Something went wrong!";
                            }

                        } else {
                            DB::rollback();

                            $response['message'] = "Something went wrong!";
                        }

                    }
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function draw(Request $request, $raffle_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    // manually draw winners
                    $response = RaffleRepository::draw($raffle_id);
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function signupPlus(Request $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {
                if ($request->isMethod('post')) {
                    $form = $request->all();

                    // get signup details
                    $signup = RaffleSignup::getSignupDetails($form['raffle'], $form['code']);

                    if (count($signup->first())) {
                        if (RaffleAction::validateAction($form['raffle'], $form['facebook_share_id'])) {

                            if (RaffleEntry::validateEntry($signup->raffle_signup_id, $form['facebook_share_id'])) {
                                $raffle_entry = [
                                    'raffle_signup_id' => $signup->raffle_signup_id,
                                    'raffle_action_id' => $form['facebook_share_id'],
                                    'is_active'        => 1,
                                    'created_at'       => date('Y-m-d H:i:s'),
                                    'updated_at'       => date('Y-m-d H:i:s')
                                ];

                                if (RaffleEntry::createEntry($raffle_entry)) {
                                    $response = ['success' => true, 'message' => 'You have additional raffle entry by sharing this raffle to facebook. Thank you.'];
                                }
                            } else {
                                $response = ['success' => true, 'message' => 'Thank you for sharing this raffle to facebook.'];
                            }
                        }
                    } else {
                        $response['message'] = "Invalid action.";
                    }
                }
            } else {
                $response['message'] = "Invalid signup.";
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }
}
