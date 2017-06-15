<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendForgotEmailRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Simsdm;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use View;

class UserController extends MainController {
    public function __construct()
    {
        parent::__construct();

        array_push($this->css['pages'], 'css/pages/sign.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'js/pages/blankon.sign.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
    }

    public function index()
    {
        return view('user.user-list');
    }

    public function showLoginForm()
    {
        if (Auth::user()) return redirect()->intended('/');

        $page_title = 'Login';

        return view('user.login', compact('page_title'));
    }

    public function doLogin(UserLoginRequest $request)
    {
        Auth::attempt(
            [
                'username' => $request->username,
                'password' => $request->password
            ],
            $request->remember_me);

        $request->session()->flash('alert-success', 'Selamat datang di SIMKERMA USU');

        return redirect()->intended('/');
    }

    public function doLogout()
    {
        if (Auth::user()) Auth::logout();

        return redirect()->intended('/');
    }

    public function showForgotPasswordForm()
    {
        return ('user.forgot');
    }

    public function sendForgotEmail(SendForgotEmailRequest $request)
    {
//        if (isset($request->username))
//        {
//            $user_account = User::where('username', $request->username)->first()->userAccount()->first();
//        } else
//        {
//            $user_account = UserAccount::where('email', $request->email)->first();
//        }
//
//        DB::transaction(function () use ($user_account, $request)
//        {
//            $password_reset = PasswordReset::where('username', $request->username)->first();
//            if (is_null($password_reset))
//            {
//                $password_reset = new PasswordReset();
//                $password_reset->username = $user_account->user()->first()->username;
//                $password_reset->token = sha1(bcrypt($password_reset->username));
//                $password_reset->created_at = Carbon::now()->toDateTimeString();
//                $password_reset->save();
//            }
//
//            //Send Email
//            $recipients = $user_account->email;
//            $email['subject'] = '[ISICASH] Permintaan Reset Password';
//            $email['full_name'] = $user_account->full_name;
//            $email['body_content'] = 'Berikut adalah link untuk melakukan reset password : <a href="' . URL::to('user/reset?username=' . $password_reset->username . '&token=' . $password_reset->token) . '" target="_blank">reset password</a>.
//            Jika anda tidak mengenali atau tidak melakukan reset password, harap mengabaikan email ini.';
//            $email['footer'] = 'Terima kasih';
//
//            dispatch(new SendRegistrationJob($recipients, $email));
//        });
//        $request->session()->flash('alert-success', 'Email telah dikirim, silahkan cek email anda!');
//
//        return redirect()->intended('user/login');
    }

    public function store(StoreUserRequest $request)
    {
        $input = Input::get();

        $user = new User();
        $user->fill($input);
        $user->password = bcrypt($request->password);
        $user->save();
    }
}
