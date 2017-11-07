<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendForgotEmailRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Simsdm;
use App\UserAuth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Psy\Input\ShellInput;
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

        $auth = UserAuth::where('username',$this->user_info['username'])->get();

        if(!$auth->contains('auth_type','SU') && !$auth->contains('auth_type','SAU')){
            return abort('403');
        }

        return view('user.user-list', compact('page_title'));
    }

    public function getAjax()
    {
        $auth = UserAuth::where('username',$this->user_info['username'])->where('deleted_at',null)->get();
        $user_auths = null;

        if($auth->contains('auth_type','SU')){
            $user_auths = UserAuth::all();
        }elseif ($auth->contains('auth_type','SAU')){
            $user_auths = UserAuth::where('auth_type','!=','SU')->get();
        }else{
            $user_auths = UserAuth::where('auth_type','!=','SU')->where('created_by',$auth[0]->username)->get();
        }

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
        $units = $simsdm->unitAll();


        $isSuper = null;
        $authentication= null;
        $user_authentication = UserAuth::where('username',$this->user_info['username'])->where('deleted_at',null)->get();
        if($user_authentication->contains('auth_type','SU')){
            $isSuper=true;
            $authentication= 'SU';
        }elseif($user_authentication->contains('auth_type','SAU')){
            $authentication = 'SAU';
        } elseif($user_authentication->contains('auth_type','AU')){
            $authentication = 'AU';
        }elseif(!$user_authentication->contains('auth_type','AU')){
            return abort('404');
        }

        return view('user.user-detail', compact(
            'page_title',
            'auths',
            'upd_mode',
            'action_url',
            'units',
            'isSuper',
            'authentication'
        ));
    }

    public function store(StoreUserRequest $request)
    {
        $user_auths = new Collection();
        $simsdm = new Simsdm();
        $units = $simsdm->unitAll();
        foreach ($request->input('auth_type') as $key => $item)
        {
            $user_auth = new UserAuth();
            $user_auth->username = $request->username;
            $user_auth->auth_type = $request->input('auth_type')[$key];

            $unit = $request->input('unit')[$key];
            $user_auth->unit = $unit;

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
        $units = $simsdm->unitAll();
      
        $isSuper = null;
        $authentication = null;
        $user_authentication = UserAuth::where('username',$this->user_info['username'])->where('deleted_at',null)->get();
        if($user_authentication->contains('auth_type','SU')){
            $isSuper=true;
            $authentication = 'SU';
        }elseif($user_authentication->contains('auth_type','SAU')){
            $authentication = 'SAU';
        } elseif($user_authentication->contains('auth_type','AU')){
            $authentication = 'AU';
        }elseif(!$user_authentication->contains('auth_type','AU')){
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
            'user_auths',
            'user_auth',
            'isSuper',
            'authentication'
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
            $simsdm = new Simsdm();
            $units = $simsdm->unitAll();
            foreach ($request->input('auth_type') as $key => $item)
            {
                $user_auth = new UserAuth();
                $user_auth->username = $request->username;
                $user_auth->auth_type = $request->input('auth_type')[$key];
                $unit = $request->input('unit')[$key];
                $user_auth->unit = $unit;
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
}
