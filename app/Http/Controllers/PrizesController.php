<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormPrizesRequest;
use App\Prize;
use App\Action;
use App\PrizeRoster;


class PrizesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $prizes      = Prize::getPrizes($this->per_page);
        $prize_list  = Prize::getPrizesList();
        $action_list = Action::getActionList($this->per_page);

        return view('Prizes.index', compact('prizes', 'prize_list', 'action_list'));
    }

    public function create(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $form = view('Partials.Forms._prize')->render();

                $response = ['success' => true, 'form' => $form];
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function store(FormPrizesRequest $request)
    {
        $response = ['success' => false, 'message' => 'Invalid request'];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    $form = $request->all();
                    $file = \Request::file('image');

                    if (Prize::checkDuplicate($form['name']) == false) {
                        $response['message'] = "Prize {$form['name']} is already existing.";
                        return response()->json($response);
                    }

                    if ($request->hasFile('image') and $request->file('image')->isValid()) {
                        $filename = $file->getClientOriginalName();
                        $file->move('uploads/prize', $filename);
                        $path = "prize/{$filename}";
                    } else {
                        $path = null;
                    }

                    $data = [
                        'name'        => $form['name'],
                        'amount'      => $form['amount'],
                        'image'       => $path,
                        'is_active'   => 1
                    ];

                    $id = Prize::createPrize($data);

                    if ($id) {
                        $response = ['success' => true, 'message' => 'Prize has been added to the database'];
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

                $raffle_prize_id = $request->get('id');

                $prize = Prize::getPrize($raffle_prize_id);
                $image = \Storage::disk('local')->url($prize->image);

                if (count($prize)) {
                    $form = view('Partials.Forms._prize_edit', compact('prize', 'image'))->render();
                } else {
                    $form = view('Partials.Forms._prize')->render();
                }

                $response = ['success' => true, 'form' => $form];
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function updatePrize(FormPrizesRequest $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    $form = $request->all();

                    $prize_info = Prize::where(['raffle_prize_id' => $form['id'], 'is_active' => 1]);

                    if ($prize_info->count()) {
                        $prize = $prize_info->first();
                        $prize->name        = $form['name'];
                        $prize->amount      = $form['amount'];

                        $file = \Request::file('image');

                        if ($request->hasFile('image') and $request->file('image')->isValid()) {
                            $filename = $file->getClientOriginalName();
                            $file->move('uploads/prize', $filename);
                            $prize->image = "prize/{$filename}";
                        }

                        if ($prize->save()) {
                            $response = ['success' => true, 'message' => 'Prize updated!'];
                        }
                    }
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function destroy(FormPrizesRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('delete')) {

                    $form = $request->all();

                    $prize_info = Prize::where(['raffle_prize_id' => $form['id']]);

                    if ($prize_info->count()) {
                        $prize_info->delete();

                        $response = ['success' => true, 'message' => 'Prize deleted!'];
                    }

                }

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function assign(Request $request)
    {
        $response = ['success' => false];

        if ($request->ajax()) {
            if ($request->isMethod('post')) {
                $form = $request->all();

                $data = null;

                foreach ($form['selected']['raffle_prize_id'] as $i => $prize) {
                    $data[] = [
                        'raffle_id' => $form['selected']['raffle_id'],
                        'raffle_prize_id' => $prize,
                        'order' => $form['selected']['order'][$i]
                    ];
                }

                if (!is_null($data)) {
                    foreach ($data as $d) {
                        if (!PrizeRoster::checkDuplicate($d['raffle_id'], $d['raffle_prize_id'])) {
                            PrizeRoster::createPrizeRoster($d);
                        } else {
                            $prize_obj = PrizeRoster::getRafflePrize($d['raffle_id'], $d['raffle_prize_id']);
                            $prize = $prize_obj->first();
                            $prize->order = $d['order'];
                            $prize->save();
                        }
                    }

                    if (count($data) > 1) {
                        $message = "Prizes are assigned to the raffle";
                    } else {
                        $message = "Prize is assigned to the raffle";
                    }

                    $response = ['success' => true, 'message' => $message];
                }
            }
        }

        return response()->json($response);
    }

    public function deassignPrize(Request $request, $raffle_id, $raffle_prize_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('delete')) {

                    $raffle_prize = PrizeRoster::getRafflePrize($raffle_id, $raffle_prize_id);

                    if (count($raffle_prize)) {
                        PrizeRoster::find($raffle_prize->id)->delete();

                        $prizes = PrizeRoster::getRafflePrizes($raffle_id);

                        $row = view('Partials.Prizes._raffle_prizes_row', compact('prizes'))->render();

                        $response = ['success' => true, 'message' => 'Prize has been removed', 'data' => ['row' => $row]];
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function getRafflePrizes(Request $request, $raffle_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $prizes = PrizeRoster::getRafflePrizes($raffle_id);

                $row = view('Partials.Prizes._raffle_prizes_row', compact('prizes'))->render();

                $response = ['success' => true, 'data' => ['row' => $row]];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function createSelectedRow(Request $request, $raffle_prize_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $prize = Prize::getPrize($raffle_prize_id);

                if (count($prize)) {
                    $html_row = view('Partials.Prizes._row', compact('prize'))->render();

                    $response = ['success' => true, 'data' => ['row' => $html_row]];
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function reloadList(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $prizes = Prize::getPrizes($this->per_page);

                $list = view('Partials.Prizes._list', compact('prizes'))->render();

                $response = ['success' => true, 'data' => ['list' => $list]];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function upload(Request $request)
    {
        echo '<pre>'; print_r($request->all()); exit;
    }

    public function search(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {
                    $needle = $request->get('search');

                    $prizes = Prize::getPrizes($this->per_page, $needle);

                    $list = view('Partials.Prizes._list', compact('prizes'))->render();

                    $response = ['success' => true, 'data' => ['list' => $list]];
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }
}
