<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendForgotEmailRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Simsdm;
use App\User;
use App\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use View;

class UserController extends MainController {
    public function __construct()
    {
        $this->middleware('is_auth');
        parent::__construct();

        array_push($this->css['pages'], 'css/pages/sign.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jquery-ui/themes/base/jquery-ui.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/select2/select2.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/datatables.responsive.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/select2/select2.full.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/jquery-ui.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/ui/minified/autocomplete.min.js');

        array_push($this->js['scripts'], 'js/pages/blankon.sign.js');
        array_push($this->js['scripts'], 'js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
    }

    public function index()
    {
        $page_title = 'User';

        return view('user.user-list', compact('page_title'));
    }

    public function getAjax()
    {
        $user_auths = UserAuth::all();
        $users = $user_auths->unique('username');
        $simsdm = new Simsdm();

        $data = [];

        $i = 0;
        foreach ($users as $user)
        {
            $data['data'][$i][0] = $i + 1;
            $data['data'][$i][1] = $user->username;
            $data['data'][$i][2] = $simsdm->getEmployee($user->username)->full_name;
            $auths = $user_auths->filter(function ($v, $k) use ($user)
            {
                return $v->username == $user->username;
            });
            $data['data'][$i][3] = '';
            foreach ($auths as $auth)
            {
                $data['data'][$i][3] = $data['data'][$i][3] . $auth->auth_type . ' ';
            }
            $data['data'][$i][3] = rtrim($data['data'][$i][3]);
            $i++;
        }

        $count_data = count($data);
        if ($count_data == 0)
        {
            $data['data'] = [];
        } else
        {
            $count_data = count($data['data']);
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function create()
    {
        $page_title = 'Tambah User';
        $auths = \App\Auth::all();
        $upd_mode = 'create';
        $action_url = 'users/create';

        $simsdm = new Simsdm();
        $faculties = $simsdm->facultyAll();
        $units = $simsdm->unitAll();
        foreach ($faculties as $faculty)
        {
            $unit['code'] = $faculty['code'];
            $unit['name'] = $faculty['name'];
            $units[] = $unit;
        }
        $study_programs = [];
        foreach ($faculties as $faculty)
        {
            $study_program = $simsdm->studyProgram($faculty['code']);
            if (! empty($study_program))
            {
                foreach ($study_program as $item)
                {
                    $study_programs[] = $item;
                }
            }
        }

        $isSuper = null;
        $authenticat = null;
        $user_authentication = UserAuth::where('username',$this->user_info['username'])->where('deleted_at',null)->get();
        if($user_authentication->contains('auth_type','SU')){
            $isSuper=true;
            $authenticat = 'SU';
        }elseif($user_authentication->contains('auth_type','SAU')){
            $authenticat = 'SAU';
        } elseif($user_authentication->contains('auth_type','AU')){
            $authenticat = 'AU';
        }elseif(!$user_authentication->contains('auth_type','AU') && $user_authentication->contains('auth_type','AP')){
            return abort('404');
        }

        return view('user.user-detail', compact(
            'page_title',
            'auths',
            'upd_mode',
            'action_url',
            'units',
            'study_programs',
            'isSuper',
            '$authenticat'
        ));
    }

    public function store(StoreUserRequest $request)
    {
        $user_auths = new Collection();
        foreach ($request->input('auth_type') as $key => $item)
        {
            $user_auth = new UserAuth();
            $user_auth->username = $request->username;
            $user_auth->auth_type = $request->input('auth_type')[$key];
            $user_auth->unit = $request->input('unit')[$key];
            $user_auth->created_by = Auth::user()->username;
            $user_auths->push($user_auth);
        }
        
        $user_auths = $user_auths->unique(function ($item)
        {
            return $item['auth_type'] . $item['unit'];
        });
        DB::transaction(function () use ($user_auths)
        {
            foreach ($user_auths as $user_auth)
            {
                $user_auth->save();
            }
        });
        $request->session()->flash('alert-success', 'User berhasil dibuat!');

        return redirect()->intended('users');
    }

    public function edit()
    {
        $input = Input::get('id');
        $user_auths = UserAuth::where('username', $input)->get();
        if ($user_auths->isEmpty())
        {
            return abort('404');
        }

        $page_title = 'Edit User';
        $auths = \App\Auth::all();
        $upd_mode = 'edit';
        $action_url = 'users/edit';

        $simsdm = new Simsdm();
        $faculties = $simsdm->facultyAll();
        $units = $simsdm->unitAll();
        foreach ($faculties as $faculty)
        {
            $unit['code'] = $faculty['code'];
            $unit['name'] = $faculty['name'];
            $units[] = $unit;
        }
        $study_programs = [];
        foreach ($faculties as $faculty)
        {
            $study_program = $simsdm->studyProgram($faculty['code']);
            if (! empty($study_program))
            {
                foreach ($study_program as $item)
                {
                    $study_programs[] = $item;
                }
            }
        }
        $isSuper = null;
        $authenticat = null;
        $user_authentication = UserAuth::where('username',$this->user_info['username'])->where('deleted_at',null)->get();
        if($user_authentication->contains('auth_type','SU')){
            $isSuper=true;
            $authenticat = 'SU';
        }elseif($user_authentication->contains('auth_type','SAU')){
            $authenticat = 'SAU';
        } elseif($user_authentication->contains('auth_type','AU')){
            $authenticat = 'AU';
        }elseif(!$user_authentication->contains('auth_type','AU') && $user_authentication->contains('auth_type','AP')){
            return abort('404');
        }

        $user_auth = UserAuth::where('username', $input)->first();
        $user_auth->username_display = $user_auth->username;
        $employee = $simsdm->getEmployee($user_auth->username);
        $user_auth->full_name = $employee->full_name;



        return view('user.user-detail', compact(
            'page_title',
            'auths',
            'upd_mode',
            'action_url',
            'units',
            'study_programs',
            'user_auths',
            'user_auth',
            'isSuper',
            'authenticat'
        ));
    }

    public function update(StoreUserRequest $request)
    {
        UserAuth::where('username', $request->username)->delete();
        if (is_null($request->input('auth_type')))
        {
            $request->session()->flash('alert-success', 'User berhasil dihapus');

            return redirect()->intended('users');
        } else
        {
            $user_auths = new Collection();
            foreach ($request->input('auth_type') as $key => $item)
            {
                $user_auth = new UserAuth();
                $user_auth->username = $request->username;
                $user_auth->auth_type = $request->input('auth_type')[$key];
                $user_auth->unit = $request->input('unit')[$key];
                $user_auth->created_by = Auth::user()->username;
                $user_auths->push($user_auth);
            }
            DB::transaction(function () use ($user_auths)
            {
                foreach ($user_auths as $user_auth)
                {
                    $user_auth->save();
                }
            });
            $request->session()->flash('alert-success', 'User berhasil dibuat!');

            return redirect()->intended('users');
        }
    }

    public function destroy()
    {
        $input = Input::get('username');
        $user_auths = UserAuth::where('username', $input)->get();
        if ($user_auths->isEmpty())
        {
            return abort('404');
        }
        UserAuth::where('username', $input)->delete();
        session()->flash('alert-success', 'User berhasil dihapus');

        return redirect()->intended('users');
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

    public function searchUser()
    {
        $input = Input::get();
        $simsdm = new Simsdm();
        $users = $simsdm->searchEmployee($input['query'], $input['limit']);

        $results = new Collection();
        foreach ($users->data as $user)
        {
            $result = new \stdClass();
            $result->username = $user->nip;
            $result->full_name = $user->full_name;
            $result->label = 'NIP: ' . $user->nip . ', NIDN: ' . $user->nidn . ', Nama: ' . $user->full_name;
            $results->push($result);
        }
        $results = json_encode($results, JSON_PRETTY_PRINT);

        return response($results, 200)->header('Content-Type', 'application/json');
    }

    private function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val)
        {
            if (! in_array($val[$key], $key_array))
            {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        return $temp_array;
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

//    public function store(StoreUserRequest $request)
//    {
//        $input = Input::get();
//
//        $user = new User();
//        $user->fill($input);
//        $user->password = bcrypt($request->password);
//        $user->save();
//    }
}
