<?php

namespace App\Http\Controllers;

use App\Raffle;
use App\RaffleAction;
use Illuminate\Http\Request;
use App\Prize;
use App\Action;
use App\Http\Requests\FormActionRequest;


class ActionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $actions     = Action::getActionList($this->per_page);
        $prize_list  = Prize::getPrizesList();
        $action_list = Action::getActionList($this->per_page);

        return view('Actions.index',  compact('actions', 'prize_list', 'action_list'));
    }

    public function update(FormActionRequest $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {

                if ($request->isMethod('put')) {
                    $form = $request->all();

                    $action_info = Action::where(['action_id' => $form['id'], 'is_active' => 1]);

                    if ($action_info->count()) {
                        $action = $action_info->first();
                        $action->name        = $form['name'];

                        if (isset($form['default'])) {
                            // reset current default
                            $def = Action::where('is_default', 1)->first();
                            $def->is_default = 0;
                            $def->save();

                            $action->is_default = 1;
                        } else {
                            $action->is_default = 0;
                        }

                        if ($action->save()) {
                            $response = ['success' => true, 'message' => 'Action updated!'];
                        }
                    }
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function destroy(FormActionRequest $request, $action_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('delete')) {

                    $action_info = Action::where(['action_id' => $action_id]);

                    if ($action_info->count()) {
                        $action_info->delete();

                        $actions = Action::getActionList($this->per_page);

                        $list = view('Partials.Actions._list', compact('actions'))->render();

                        $response = ['success' => true, 'message' => 'Action has been deleted!', 'data' => ['list' => $list]];
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function store(FormActionRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {

                    $form = $request->all();

                    $form['value'] = 1;

                    if (isset($form['is_default'])) {
                        $form['is_default'] = 1;
                    } else {
                        $form['is_default'] = 0;
                    }

                    // reset existing is default
                    if ($form['is_default']) {
                        $def = Action::where('is_default', 1)->first();
                        $def->is_default = 0;
                        $def->save();
                    }

                    if (Action::createAction($form)) {
                        $response = ['success' => true, 'message' => 'New action successfully added'];
                    }

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

                $actions = Action::getActionList($this->per_page);

                $list = view('Partials.Actions._list', compact('actions'))->render();

                $response = ['success' => true, 'data' => ['list' => $list]];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function assignAction(FormActionRequest $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {

                $actions = Action::getActionList($this->per_page);

                $list = view('Partials.Actions._list', compact('actions'))->render();

                $response = ['success' => true, 'data' => ['list' => $list]];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function createSelectedRow(FormActionRequest $request, $raffle_action_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $action = Action::getAction($raffle_action_id);

                if (count($action)) {
                    $html_row = view('Partials.Actions._row', compact('action'))->render();

                    $response = ['success' => true, 'data' => ['row' => $html_row]];
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function getRaffleActions(FormActionRequest $request, $raffle_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $actions = RaffleAction::getRaffleActions($raffle_id, false, false);

                $row = view('Partials.Actions._raffle_actions_row', compact('actions'))->render();

                $response = ['success' => true, 'data' => ['row' => $row]];
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

                foreach ($form['selected']['action_id'] as $i => $action) {
                    $data[] = [
                        'raffle_id' => $form['selected']['raffle_id'],
                        'action_id' => $action,
                        'is_active' => 1
                    ];
                }

                if (!is_null($data)) {
                    foreach ($data as $d) {
                        if (!RaffleAction::checkDuplicate($d['raffle_id'], $d['action_id'])) {
                            RaffleAction::saveRaffleAction($d);
                        }
                    }

                    if (count($data) > 1) {
                        $message = "Actions are assigned to the raffle";
                    } else {
                        $message = "Actions is assigned to the raffle";
                    }

                    $response = ['success' => true, 'message' => $message];
                }
            }
        }

        return response()->json($response);
    }

    public function deassignAction(FormActionRequest $request, $raffle_id, $raffle_action_id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('delete')) {

                    $raffle_action = RaffleAction::getRaffleActionData($raffle_action_id);

                    if (count($raffle_action)) {
                        RaffleAction::find($raffle_action->id)->delete();

                        $actions = RaffleAction::getRaffleActions($raffle_id, false, false);

                        $row = view('Partials.Actions._raffle_actions_row', compact('actions'))->render();

                        $response = ['success' => true, 'message' => 'Action has been removed', 'data' => ['row' => $row]];
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }
}
