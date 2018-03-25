<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\Prize;
use App\Action;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Login.
     *
     * @return void
     */
    public function login(Request $request)
    {
        try {
            $prize_list  = Prize::getPrizesList();
            $action_list = Action::getActionList($this->per_page);

            if ($request->isMethod('post')) {
                $form = $request->all();

                $attempt = ['email' => $form['email'], 'password' => $form['password'], 'is_active' => 1, 'is_deleted' => 0];

                if (\Auth::attempt($attempt)) {
                    // Authentication passed...
                   return redirect()->intended($this->redirectTo);
                } else {
                    $request->session()->flash('errors', 'Invalid username/password');
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return view('Login.login', compact('prize_list', 'action_list'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user_info = User::where(['id' => \Auth::user()->id]);

        if ($user_info->count()) {
            $user_info->update(['last_login' => date('Y-m-d H:i:s')]);
        }

        $this->guard()->logout();

        return redirect('/');
    }
}
