<?php

namespace App\Http\Controllers;

use App\Approval;
use App\AreasCoop;
use App\Cooperation;
use App\CoopItem;
use App\CoopType;
use App\Http\Requests\StoreCooperationRequest;
use App\Partner;
use App\Simsdm;
use App\User;
use App\UserAuth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use parinpan\fanjwt\libs\JWTAuth;
use View;
use File;

class CooperationController extends MainController {

    public function __construct()
    {
        $this->middleware('is_auth')->except('soonEndsList');

        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/select2/select2.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/select2/select2.full.min.js');

        array_push($this->js['scripts'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/datatables/datatables.responsive.js');

        array_push($this->js['scripts'], 'js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
    }

    public function index()
    {
        if (env('APP_ENV') == 'local')
        {
            $login = new \stdClass();
            $login->logged_in = true;
            $login->payload = new \stdClass();
            // $login->payload->identity = env('LOGIN_USERNAME');
            // $login->payload->user_id = env('LOGIN_ID');
            $login->payload->identity = env('USERNAME_LOGIN');
            $login->payload->user_id = env('ID_LOGIN');
        } else
        {
            $login = JWTAuth::communicate('https://akun.usu.ac.id/auth/listen', @$_COOKIE['ssotok'], function ($credential)
            {
                $loggedIn = $credential->logged_in;
                if ($loggedIn)
                {
                    return $credential;
                } else
                {
                    setcookie('ssotok', null, -1, '/');

                    return false;
                }
            }
            );
        }

        if (!$login)
        {
            $login_link = JWTAuth::makeLink([
                'baseUrl'  => 'https://akun.usu.ac.id/auth/login',
                'callback' => url('/') . '/callback.php',
                'redir'    => url('/'),
            ]);

            return view('landing-page', compact('login_link'));
        } else
        {
            $user = new User();
            $user->username = $login->payload->identity;
            $user->user_id = $login->payload->user_id;
            Auth::login($user);

            $this->setUserInfo();

            $page_title = 'Kerjasama';
            $auth = null;
            $isOperator = false;

            $user_auth = $this->getUserAuth();

            if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
                $auth = 'SU';
            }else{
                $auth = 'Admin';
            }

            if($user_auth->isNotEmpty()){
                $isOperator = true;
                $auth = 'Others';
            }

            return view('cooperation.coop-list', compact('page_title','auth','isOperator'));
        }
    }

    public function soonEndsList()
    {
        if (env('APP_ENV') == 'local')
        {
            $login = new \stdClass();
            $login->logged_in = true;
            $login->payload = new \stdClass();
            // $login->payload->identity = env('LOGIN_USERNAME');
            // $login->payload->user_id = env('LOGIN_ID');
            $login->payload->identity = env('USERNAME_LOGIN');
            $login->payload->user_id = env('ID_LOGIN');
        } else
        {
            $login = JWTAuth::communicate('https://akun.usu.ac.id/auth/listen', @$_COOKIE['ssotok'], function ($credential)
            {
                $loggedIn = $credential->logged_in;
                if ($loggedIn)
                {
                    return $credential;
                } else
                {
                    setcookie('ssotok', null, -1, '/');

                    return false;
                }
            }
            );
        }

        if (!$login)
        {
            $login_link = JWTAuth::makeLink([
                'baseUrl'  => 'https://akun.usu.ac.id/auth/login',
                'callback' => url('/') . '/callback.php',
                'redir'    => url('/'),
            ]);

            return view('landing-page', compact('login_link'));
        } else{
            $user = new User();
            $user->username = $login->payload->identity;
            $user->user_id = $login->payload->user_id;
            Auth::login($user);

            $this->setUserInfo();

            $page_title = 'Kerjasama Segera Berakhir';

            $user_auth = $this->getUserAuth();
            $auth = null;

            if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
                $auth = 'SU';
            }else{
                $auth = 'Admin';
            }

            return view('cooperation.coop-soon-ends', compact('page_title','auth'));
        }
    }

    public function approveList()
    {
        $page_title = 'Approve Kerjasama';

        $user_auth = $this->getUserAuth();
        $auth = null;

        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
            $auth = 'SU';
        }else{
            $auth = 'Admin';
        }

        return view('cooperation.coop-approve-list', compact('page_title','auth'));
    }

    public function create()
    {
        $user_auth = $this->getUserAuth();
        if($user_auth->isEmpty())
            return abort('403');

        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');
        array_push($this->css['pages'], 'css/jquery-confirm.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'kartik-v/bootstrap-fileinput/js/fileinput.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');
        array_push($this->js['scripts'], 'js/jquery-confirm.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);

        $page_title = "Tambah Kerjasama";
        $upd_mode = 'create';
        $action_url = 'cooperations/create';

        $simsdm = new Simsdm();
        $partner_id_Coop = Cooperation::select('partner_id')->where('coop_type','MOU')->where('deleted_at',null)->get();
        $partners = Partner::whereNotIn('id', $partner_id_Coop)->get();
        $coop_types = CoopType::all();
        $mou_coops = Cooperation::where('coop_type', 'MOU')->where('status','AC')->with('partner')->get();

        $moa_coops = new Collection();
        $spk_coops = new Collection();
        foreach ($user_auth as $user){
            if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                $moa_coops = Cooperation::where('coop_type', 'MOA')->where('status','AC')->get();
                $spk_coops = Cooperation::where('coop_type', 'SPK')->where('status','AC')->get();
            }else{
                $moa_coop = Cooperation::where('coop_type', 'MOA')->where('unit',$user->unit)->where('status','AC')->get();
                $spk_coop = Cooperation::where('coop_type', 'SPK')->where('unit',$user->unit)->where('status','AC')->get();

                if($moa_coop){
                    $merged = $moa_coops->merge($moa_coop);
                    $moa_coops = $merged;
                }elseif($spk_coop){
                    $merged = $spk_coops->merge($spk_coop);
                    $spk_coops = $merged;
                }
            }
        }

        $coop_items = new Collection();
        $coop_item = new CoopItem();
        $coop_items->add($coop_item);

        $isSuper = null;
        $isOperator = false;

        $user_auth = $this->getUserAuth();

        if($user_auth->isNotEmpty()){
            $isOperator = true;
        }

        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
            $isSuper = true;
        }

        $units = [];

        if($isSuper){
            $units = $simsdm->unitAll();
            $usu = array("id"=>"","code"=>"USU","name"=>"Universitas Sumatera Utara");
            array_push($units,$usu);
        }elseif($user_auth->contains('auth_type','AU')){
            foreach ($user_auth as $user){
                $l_units = $simsdm->unitAll();

                foreach ($l_units as $key=>$unit){
                    if (is_array($l_units) && !in_array($user->unit, $unit)){
                        unset($l_units[$key]);
                    }
                }
                $units = array_merge($units, $l_units);
            }
        }
        $areas = AreasCoop::get();

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'partners',
            'coop_types',
            'mou_coops',
            'moa_coops',
            'spk_coops',
            'units',
            'coop_items',
            'isSuper',
            'isOperator',
            'areas'
        ));
    }

    public function store(StoreCooperationRequest $request)
    {
        $input = Input::all();

        DB::transaction(function () use ($input, $request)
        {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['created_by'] = Auth::user()->user_id;
            $date1 = str_replace('/', '-', $cooperation['sign_date']);
            $cooperation['sign_date'] = date('Y-m-d', strtotime($date1));
            $date2 = str_replace('/', '-', $cooperation['end_date']);
            $cooperation['end_date'] = date('Y-m-d', strtotime($date2));
            if($this->isAdmin($this->user_info['user_id'])){
                $cooperation->status = 'AC';
            }else{
                $cooperation->status = 'SB';
            }
            $cooperation->subject_of_coop = $cooperation['subject_of_coop'];
            $cooperation->contract_amount = 0;
            if ($cooperation->coop_type == 'ADDENDUM')
                $relation_coop = Cooperation::find($input['cooperation_id']);

            if (
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'SPK' || $cooperation->coop_type == 'SPK') ||
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'MOA' || $cooperation->coop_type == 'MOA')
            )
            {
                if (isset($input['item_name']))
                {
                    $coop_items = new Collection();
                    foreach ($input['item_name'] as $key => $item)
                    {
                        $coop_item = new CoopItem();
                        $coop_item->item = $key+1;
                        $coop_item->item_name = $input['item_name'][$key];

                        $coop_item->item_quantity = str_replace(',', '', $input['item_quantity'][$key]);
                        $coop_item->item_uom = $input['item_uom'][$key];
                        $coop_item->item_total_amount = str_replace(',', '', $input['item_total_amount'][$key]);
                        $coop_item->item_annotation = $input['item_annotation'][$key];
                        $cooperation->contract_amount += $coop_item->item_total_amount;
                        $coop_items->add($coop_item);
                    }
                }
            }

            $file = $request->file('file_name_ori');

            if ($cooperation->coop_type == 'ADDENDUM'){
                if(!isset($file)){
                    $cooperation->file_name_ori = $relation_coop->file_name_ori;
                    $cooperation->file_name = $relation_coop->file_name;
                }else{
                    $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                    $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
                }
            }else{
                $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
            }

            if (!$cooperation->coop_type == 'SPK'){
                $cooperation->benefit = $input['benefit'];
            }

            $cooperation->save();

            if (isset($coop_items)){
                $cooperation->coopItem()->saveMany($coop_items);
            }

            if ($cooperation->coop_type == 'MOU')
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'MOA')
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'SPK')
                $path = Storage::url('upload/' . 'SPK/' . $cooperation->id);
            else
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

            if($cooperation->coop_type == 'ADDENDUM'){
                if(!isset($file)){
                    $path1 = Storage::url('upload/'. $relation_coop->coop_type .'/'. $input['cooperation_id'] .'/'. $relation_coop->file_name);
                    $path2 = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id . '/'. $relation_coop->file_name);
                    Storage::copy($path1, $path2);
                }else{
                    $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
                }
            }else{
                if (! is_null($request->file('file_name_ori')))
                {
                    $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
                }
            }
        });

        $request->session()->flash('alert-success', 'Kerjasama berhasil dibuat');

        return redirect()->intended('/cooperations');
    }

    public function storeTemp(Request $request)
    {
        $input = Input::all();

        DB::transaction(function () use ($input, $request) {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['created_by'] = Auth::user()->user_id;
            $date1 = str_replace('/', '-', $cooperation['sign_date']);
            $cooperation['sign_date'] = date('Y-m-d', strtotime($date1));
            $date2 = str_replace('/', '-', $cooperation['end_date']);
            $cooperation['end_date'] = date('Y-m-d', strtotime($date2));
            $cooperation->status = 'SS';
            $cooperation->contract_amount = 0;

            if ($cooperation->coop_type == 'ADDENDUM')
                $relation_coop = Cooperation::find($input['cooperation_id']);

            if (
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'SPK' || $cooperation->coop_type == 'SPK') ||
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'MOA' || $cooperation->coop_type == 'MOA')
            ){
                if(isset($input['item_name'][0])){
                    if (!empty(($input['item_name'][0])))
                    {
                        $coop_items = new Collection();
                        foreach ($input['item_name'] as $key => $item)
                        {
                            if (!isset($cooperation->id))
                            {
                                $coop_item = new CoopItem();
                            }
                            else{
                                $cooperation->coopItem()->delete();
                                $coop_item = new CoopItem();
                            }

                            $coop_item->item = $key+1;
                            $coop_item->item_name = $input['item_name'][$key];
                            $coop_item->item_quantity = str_replace(',', '', $input['item_quantity'][$key]);
                            $coop_item->item_uom = $input['item_uom'][$key];

                            if(isset($input['item_total_amount'][$key])){
                                $coop_item->item_total_amount = str_replace(',', '', $input['item_total_amount'][$key]);
                            }else{
                                $coop_item->item_total_amount = 0;
                            }

                            $coop_item->item_annotation = $input['item_annotation'][$key];
                            $cooperation->contract_amount += $coop_item->item_total_amount;
                            $coop_items->add($coop_item);
                        }
                    }
                }
            }

            $file = $request->file('file_name_ori');
            if ($cooperation->coop_type == 'ADDENDUM'){
                if(!isset($file)){
                    $cooperation->file_name_ori = $relation_coop->file_name_ori;
                    $cooperation->file_name = $relation_coop->file_name;
                }else{
                    $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                    $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
                }
            }else{
                if(!is_null($file)){
                    $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                    $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
                }
            }

            if (!$cooperation->coop_type == 'SPK'){
                $cooperation->benefit = $input['benefit'];
            }

            $cooperation->save();

            if (isset($coop_items))
            {
                $cooperation->coopItem()->saveMany($coop_items);
            }

            if ($cooperation->coop_type == 'MOU')
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'MOA')
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'SPK')
                $path = Storage::url('upload/' . 'SPK/' . $cooperation->id);
            else
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

            if($cooperation->coop_type == 'ADDENDUM'){
                if(!isset($file)){
                    $path1 = Storage::url('upload/'. $relation_coop->coop_type .'/'. $input['cooperation_id'] .'/'. $relation_coop->file_name);
                    $path2 = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id . '/'. $relation_coop->file_name);
                    Storage::copy($path1, $path2);
                }else{
                    $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
                }
            }else{
                if (! is_null($request->file('file_name_ori')))
                {
                    $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
                }
            }
        });
        $request->session()->flash('alert-success', 'Kerjasama berhasil disimpan sementara');

        return redirect()->intended('/cooperations');
    }

    public function display()
    {
        $input = Input::all();
        $cooperation = Cooperation::find($input['id']);
        if (! isset($input['id']) || empty($cooperation))
        {
            return abort('404');
        }

        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'kartik-v/bootstrap-fileinput/js/fileinput.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);

        $page_title = "Detail Kerjasama";
        $upd_mode = 'display';
        $action_url = 'cooperations/display';
        $disabled = 'disabled';
        $coop_relations = Cooperation::where('cooperation_id', $cooperation->id)->get();

        if($this->isAdmin($this->user_info['user_id'])){
            $edit = true;
        }elseif($this->isOthers($this->user_info['user_id'])){
            $edit = false;
        }else{
            if($cooperation->coop_type=='MOU'){
                $edit = false;
            }else{
                $coop_status = Cooperation::where('cooperation_id', $cooperation->id)->where('status','RJ')->get();
                if($coop_status->isEmpty()){
                    $edit = true;
                    $rj_note = Approval::where('cooperation_id',$cooperation->id)->orderBy('id','desc')->first();
                }else{
                    $edit = false;
                }
            }
        }

        if (! $coop_relations->isEmpty())
        {
            $is_relation = 'disabled';
        }

        $simsdm = new Simsdm();
        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));
        $partners = Partner::all();
        $coop_types = CoopType::all();
        $units = $simsdm->unitAll();

        $usu = array("id"=>"","code"=>"USU","name"=>"Universitas Sumatera Utara");
        array_push($units,$usu);

        $mou_coops = Cooperation::where('coop_type', 'MOU')->get();
        $user_auth = $this->getUserAuth();

        $moa_coops = new Collection();
        $spk_coops = new Collection();
        foreach ($user_auth as $user){
            if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                $moa_coops = Cooperation::where('coop_type', 'MOA')->where('status','AC')->get();
                $spk_coops = Cooperation::where('coop_type', 'SPK')->where('status','AC')->get();
            }else{
                $moa_coop = Cooperation::where('coop_type', 'MOA')->where('unit',$user->unit)->where('status','AC')->get();
                $spk_coop = Cooperation::where('coop_type', 'SPK')->where('unit',$user->unit)->where('status','AC')->get();

                if($moa_coop){
                    $merged = $moa_coops->merge($moa_coop);
                    $moa_coops = $merged;
                }elseif($spk_coop){
                    $merged = $spk_coops->merge($spk_coop);
                    $spk_coops = $merged;
                }
            }
        }

        $coop_items = $cooperation->coopItem()->get();
        if ($cooperation->coop_type == 'ADDENDUM')
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
        }

        if($cooperation->coop_type == 'MOU')
            $coop_tree_relations = $this->getCoopRelation($input['id']);
        if($cooperation->coop_type == 'MOA' || $cooperation->coop_type == 'SPK' || $cooperation->coop_type == 'ADDENDUM' && $cooperation->cooperation_id!=null)
            $coop_tree_relations = $this->getCoopRelation($input['id']);

        $isSuper = null;
        $isOperator = false;

        $user_auth = $this->getUserAuth();
        if($user_auth->isNotEmpty())
            $isOperator = true;

        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU'))
            $isSuper = true;

        $areas = AreasCoop::get();

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'cooperation',
            'partners',
            'coop_types',
            'units',
            'mou_coops',
            'moa_coops',
            'spk_coops',
            'prev_coop',
            'coop_items',
            'coop_tree_relations',
            'disabled',
            'isSuper',
            'is_relation',
            'edit',
            'rj_note',
            'isOperator',
            'areas'
        ));
    }

    public function edit()
    {
        $user_auth = $this->getUserAuth();

        if(!$user_auth)
            return abort('403');

        $input = Input::all();
        $cooperation = Cooperation::find($input['id']);
        if (! isset($input['id']) || empty($cooperation))
        {
            return abort('404');
        }

        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');
        array_push($this->css['pages'], 'css/jquery-confirm.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'kartik-v/bootstrap-fileinput/js/fileinput.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');
        array_push($this->js['scripts'], 'js/jquery-confirm.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);

        $page_title = "Edit Kerjasama";
        $upd_mode = 'edit';
        $action_url = 'cooperations/edit';

        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));

        if($cooperation->coop_type == 'MOU')
            $coop_tree_relations = $this->getCoopRelation($input['id']);
        if($cooperation->coop_type == 'MOA' || $cooperation->coop_type == 'SPK' || $cooperation->coop_type == 'ADDENDUM' && $cooperation->cooperation_id!=null)
            $coop_tree_relations = $this->getCoopRelation($input['id']);

        $coop_relations = Cooperation::where('cooperation_id', $cooperation->id)->get();
        $partners = Partner::all();
        $coop_types = CoopType::all();
        $coop_items = $cooperation->coopItem()->get();
        $mou_coops = Cooperation::where('coop_type', 'MOU')->where('status','AC')->with('partner')->get();

        $moa_coops = new Collection();
        $spk_coops = new Collection();
        foreach ($user_auth as $user){
            if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                $moa_coops = Cooperation::where('coop_type', 'MOA')->where('status','AC')->get();
                $spk_coops = Cooperation::where('coop_type', 'SPK')->where('status','AC')->get();
            }else{
                $moa_coop = Cooperation::where('coop_type', 'MOA')->where('unit',$user->unit)->where('status','AC')->get();
                $spk_coop = Cooperation::where('coop_type', 'SPK')->where('unit',$user->unit)->where('status','AC')->get();

                if($moa_coop){
                    $merged = $moa_coops->merge($moa_coop);
                    $moa_coops = $merged;
                }elseif($spk_coop){
                    $merged = $spk_coops->merge($spk_coop);
                    $spk_coops = $merged;
                }
            }
        }

        if ($cooperation->coop_type == 'ADDENDUM')
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
        }

        $simsdm = new Simsdm();

        $disabled = null;
        if (! $coop_relations->isEmpty())
        {
            $disabled = "disabled";
            $is_relation = 'disabled';
        }

        $isSuper = null;
        $isOperator = false;

        if($user_auth->isNotEmpty())
            $isOperator = true;

        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU'))
            $isSuper = true;

        $units = [];

        if($isSuper){
            $units = $simsdm->unitAll();
            $usu = array("id"=>"","code"=>"USU","name"=>"Universitas Sumatera Utara");
            array_push($units,$usu);
        }elseif($user_auth->contains('auth_type','AU')){
            foreach ($user_auth as $user){
                $l_units = $simsdm->unitAll();

                foreach ($l_units as $key=>$unit){
                    if (is_array($l_units) && !in_array($user->unit, $unit)){
                        unset($l_units[$key]);
                    }
                }
                $units = array_merge($units, $l_units);
            }
        }

        $areas = AreasCoop::get();

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'cooperation',
            'coop_relations',
            'coop_tree_relations',
            'partners',
            'coop_types',
            'disabled',
            'units',
            'isSuper',
            'coop_items',
            'mou_coops',
            'is_relation',
            'prev_coop',
            'moa_coops',
            'spk_coops',
            'isOperator',
            'areas'
        ));
    }

    public function update(StoreCooperationRequest $request)
    {
        $input = Input::all();

        DB::transaction(function () use ($input, $request)
        {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['updated_by'] = Auth::user()->user_id;
            $cooperation['sign_date'] = date('Y-m-d', strtotime($cooperation['sign_date']));
            $cooperation['end_date'] = date('Y-m-d', strtotime($cooperation['end_date']));
            if($this->isAdmin($this->user_info['user_id'])){
                $cooperation['status'] = 'AC';
            }else{
                $cooperation['status'] = 'SB';
            }
            $cooperation->subject_of_coop = $cooperation['subject_of_coop'];
            $cooperation->contract_amount = 0;

            if ($cooperation->coop_type == 'ADDENDUM')
                $relation_coop = Cooperation::find($input['cooperation_id']);

            if (
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'SPK' || $cooperation->coop_type == 'SPK') ||
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'MOA' || $cooperation->coop_type == 'MOA')
            )
            {
                if (isset($input['item_name']))
                {
                    $coop_items = new Collection();
                    foreach ($input['item_name'] as $key => $item)
                    {
                        if (isset($cooperation->id))
                        {
                            $cooperation->coopItem()->delete();
                            $coop_item = new CoopItem();
                        }
                        else{
                            $coop_item = new CoopItem();
                        }

                        $coop_item->item = $key+1;
                        $coop_item->item_name = $input['item_name'][$key];

                        $coop_item->item_quantity = str_replace(',', '', $input['item_quantity'][$key]);
                        $coop_item->item_uom = $input['item_uom'][$key];
                        $coop_item->item_total_amount = str_replace(',', '', $input['item_total_amount'][$key]);
                        $coop_item->item_annotation = $input['item_annotation'][$key];
                        $cooperation->contract_amount += $coop_item->item_total_amount;
                        $coop_items->add($coop_item);
                    }
                }
                if (isset($coop_items))
                {
                    $cooperation->coopItem()->saveMany($coop_items);
                }
            }

            if (!$cooperation->coop_type == 'SPK'){
                $cooperation->benefit = $input['benefit'];
            }

            if (! is_null($request->file('file_name_ori'))){
                $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
            }

            if ($cooperation->coop_type == 'MOU')
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'MOA')
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'SPK')
                $path = Storage::url('upload/' . 'SPK/' . $cooperation->id);
            else
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

            if (! is_null($request->file('file_name_ori')))
            {
                $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
            }

            $cooperation->save();
        });

        $request->session()->flash('alert-success', 'Kerjasama berhasil di-update');

        return redirect()->intended('/cooperations');
    }

    public function updateTemp(Request $request)
    {
        $input = Input::all();

        DB::transaction(function () use ($input, $request) {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['updated_by'] = Auth::user()->user_id;
            $cooperation['sign_date'] = date('Y-m-d', strtotime($cooperation['sign_date']));
            $cooperation['end_date'] = date('Y-m-d', strtotime($cooperation['end_date']));
            $cooperation->subject_of_coop = $cooperation['subject_of_coop'];
            $cooperation['status'] = 'SS';

            if ($cooperation['coop_type'] == 'ADDENDUM')
                $relation_coop = Cooperation::find($input['cooperation_id']);

            if (
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'SPK' || $cooperation->coop_type == 'SPK') ||
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'MOA' || $cooperation->coop_type == 'MOA')
            )
            {
                if (isset($input['item_name']))
                {
                    $coop_items = new Collection();
                    foreach ($input['item_name'] as $key => $item)
                    {
                        if (! isset($cooperation->id))
                        {
                            $coop_item = new CoopItem();
                        } else
                        {
                            $cooperation->coopItem()->delete();
                            $coop_item = new CoopItem();
                        }

                        $coop_item->item = $key+1;
                        $coop_item->item_name = $input['item_name'][$key];
                        $coop_item->item_quantity = str_replace(',', '', $input['item_quantity'][$key]);
                        $coop_item->item_uom = $input['item_uom'][$key];

                        if(isset($input['item_total_amount'][$key])){
                            $coop_item->item_total_amount = str_replace(',', '', $input['item_total_amount'][$key]);
                        }else{
                            $coop_item->item_total_amount = 0;
                        }

                        $coop_item->item_annotation = $input['item_annotation'][$key];
                        $cooperation->contract_amount = 0;
                        $cooperation->contract_amount += $coop_item->item_total_amount;
                        $coop_items->add($coop_item);
                    }
                    if (isset($coop_items))
                    {
                        $cooperation->coopItem()->saveMany($coop_items);
                    }
                }
            }

            if (! $cooperation->coop_type == 'SPK')
                $cooperation->benefit = $input['benefit'];

            if (! is_null($request->file('file_name_ori')))
            {
                $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
            }

            $cooperation->save();

            if ($cooperation->coop_type == 'MOU')
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'MOA')
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'SPK')
                $path = Storage::url('upload/' . 'SPK/' . $cooperation->id);
            else
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

            if (! is_null($request->file('file_name_ori')))
            {
                $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
            }
        });

        $request->session()->flash('alert-success', 'Kerjasama berhasil di-update');

        return redirect()->intended('/cooperations');
    }

    public function approve()
    {
        $input = Input::all();
        $cooperation = Cooperation::find($input['id']);
        if (! isset($input['id']) || empty($cooperation))
        {
            return abort('404');
        }

        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');
        array_push($this->css['pages'], 'css/jquery-confirm.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'kartik-v/bootstrap-fileinput/js/fileinput.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');
        array_push($this->js['scripts'], 'js/jquery-confirm.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);

        $page_title = "Approve Kerjasama";
        $upd_mode = 'approve';
        $action_url = 'cooperations/approve';
        $disabled = 'disabled';
        $coop_relations = Cooperation::where('cooperation_id', $cooperation->id)->get();

        if (! $coop_relations->isEmpty())
        {
            $is_relation = 'disabled';
        }

        $simsdm = new Simsdm();
        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));
        $partners = Partner::all();
        $coop_types = CoopType::all();
        $units = $simsdm->unitAll();
        $approve = "disabled";

        $usu = array("id"=>"","code"=>"USU","name"=>"Universitas Sumatera Utara");
        array_push($units,$usu);

        $mou_coops = Cooperation::where('coop_type', 'MOU')->get();
        $moa_coops = Cooperation::where('coop_type', 'MOA')->get();
        $spk_coops = Cooperation::where('coop_type', 'SPK')->get();

        $coop_items = $cooperation->coopItem()->get();
        if ($cooperation->coop_type == 'ADDENDUM')
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
        }

        if($cooperation->coop_type == 'MOU')
            $coop_tree_relations = $this->getCoopRelation($input['id']);
        if($cooperation->coop_type == 'MOA' || $cooperation->coop_type == 'SPK' || $cooperation->coop_type == 'ADDENDUM' && $cooperation->cooperation_id!=null)
            $coop_tree_relations = $this->getCoopRelation($input['id']);

        $isSuper = null;
        $isOperator = false;

        $user_auth = $this->getUserAuth();
        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU'))
            $isSuper = true;

        if($user_auth->isNotEmpty())
            $isOperator = true;

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'cooperation',
            'partners',
            'coop_types',
            'units',
            'mou_coops',
            'moa_coops',
            'spk_coops',
            'prev_coop',
            'coop_items',
            'coop_tree_relations',
            'disabled',
            'isSuper',
            'is_relation',
            'approve',
            'isOperator'
        ));
    }

    public function approveStore(Request $request)
    {
        $this->authorize('create', Cooperation::class);
        $input = Input::all();

        $cooperation = Cooperation::find($input['id']);

        if(empty($input['id'] || empty($cooperation)))
            return abort('404');

        DB::transaction(function () use ($input, $request, $cooperation) {
            $approval = new Approval();
            $approval->cooperation_id = $request->id;
            $approval->note = $request->note;
            $cooperation->status = $request->status;

            $cooperation->save();
            $approval->save();
        });
        $request->session()->flash('alert-success', 'Kerjasama berhasil di approve');

        return redirect()->intended('cooperations/approve-list');
    }

    public function destroy()
    {
        $id = Input::get('id');
        $coop = Cooperation::find($id);
        if(empty($coop) || empty($id))
        {
            return abort('404');
        }

        if($coop->coop_type=='MOU'){
            $coop_relation = Cooperation::where('cooperation_id',$coop->id)->get();

            if(isset($coop_relation)){
                foreach($coop_relation as $coop_addendum){
                    $coop_adden = Cooperation::where('cooperation_id',$coop_addendum->id)->get();

                    if(!$coop_adden->isEmpty()){
                        $coop_adden->delete();
                    }
                    $coop_addendum->delete();
                }
            }
            $deleted = $coop->delete();
        }elseif($coop->coop_type=='MOA'){
            $coop_adden = Cooperation::where('cooperation_id',$coop->id)->get();
            if(isset($coop_adden)){
                $coop_adden->coopItem()->delete();
                $coop_adden->delete();
            }
            $coop->coopItem()->delete();
            $deleted = $coop->delete();
        }else{
            $coop->coopItem()->delete();
            $deleted = $coop->delete();
        }

        if($deleted)
            session()->flash('alert-success', 'Kerjasama berhasil dihapus');
        else
            session()->flash('alert-danger', 'Terjadi kesalahan pada sistem, Kerjasama gagal dihapus');

        return redirect()->intended('/cooperations');
    }

    public function getAjax()
    {
        $user_auth = $this->getUserAuth();

        $cooperations = new Collection();
        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU') || $user_auth->isEmpty()){
            $cooperations = Cooperation::all();
        }
        if ($user_auth->contains('auth_type','AU')){
            foreach ($user_auth as $user_a){
                $coop = Cooperation::where('unit',$user_a->unit)->get();
                $cooperations = $cooperations->merge($coop);
            }
            $cooperations_mou = Cooperation::where('coop_type','MOU')->where('status','AC')->get();
            $cooperations = $cooperations->merge($cooperations_mou);

        }
        $data = [];
        $i = 0;
        foreach ($cooperations as $cooperation)
        {
            $partner = $cooperation->partner()->first();
            $status = $cooperation->statusCode()->first();
            $area = "";
            if(!empty($cooperation->area_of_coop)){
                $area = $cooperation->areaCoop()->first();
                $area = $area->area_coop;
            }

            if ($cooperation->coop_type == 'MOA' || $cooperation->coop_type == 'SPK')
            {
                $mou_coop = Cooperation::find($cooperation->cooperation_id);
                $cooperation->form_of_coop = $mou_coop['form_of_coop'];

                if(!$mou_coop){
                    $partner = "";
                }else{
                    $partner = $mou_coop->partner()->first();
                }
            } elseif ($cooperation->coop_type == 'ADDENDUM')
            {
                $prev_coop = Cooperation::find($cooperation->cooperation_id);
                if(!empty($prev_coop))
                {
                    if ($prev_coop->coop_type == 'MOA')
                    {
                        $mou_coop = Cooperation::find($prev_coop->cooperation_id);
                        $cooperation->form_of_coop = $mou_coop->form_of_coop;
                        $partner = $mou_coop->partner()->first();
                    }
                }else{
                    $cooperation->form_of_coop = "";
                    $partner = "";
                }
            }
            $data['data'][$i][0] = $cooperation->id;
            $data['data'][$i][1] = $i + 1;
            $data['data'][$i][2] = $cooperation->subject_of_coop;
            $data['data'][$i][3] = $area;
            if(!empty($partner)){
                $data['data'][$i][4] = $partner->name;
            }else{
                $data['data'][$i][4] = "";
            }

            $data['data'][$i][5] = $cooperation->coop_type;
            $data['data'][$i][6] = $cooperation->form_of_coop;
            $data['data'][$i][7] = date('d F Y', strtotime($cooperation->end_date));
            $data['data'][$i][8] = $status->description;
            if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
                $data['data'][$i][9] = '<a href="cooperations/display?id='.$cooperation->id.'" data-toggle="tooltip" data-placement="top" title="Lihat"> <button class="btn btn-theme btn-sm rounded coop-view-btn"><i class="fa fa-eye" style="color:white;"></i></button></a>';
                $data['data'][$i][9].= '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>';
            }else{
                $data['data'][$i][9] = '<a href="cooperations/display?id='.$cooperation->id.'" data-toggle="tooltip" data-placement="top" title="Lihat"> <button class="btn btn-theme btn-sm rounded coop-view-btn"><i class="fa fa-eye" style="color:white;"></i></button></a>';
                if(!$cooperation->coop_type=='MOU'){
                    $data['data'][$i][9].= '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>';
                }
            }
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

    public function getAjaxCoopSoonEnds()
    {
        $user_auth = $this->getUserAuth();

        $cooperations = new Collection();

        $date1 = date('Y-m-d', strtotime('-1 months'));
        $date2 = date('Y-m-d', strtotime('+2 months'));

        if($user_auth->contains('auth_type','SU') || $user_auth->contains('auth_type','SAU')){
            $cooperations = Cooperation::whereBetween('end_date', [$date1, $date2])->get();
        }

        if ($user_auth->contains('auth_type','AU')){
            foreach ($user_auth as $user_a){
                $coop = Cooperation::where('unit',$user_a->unit)->whereBetween('end_date', [$date1, $date2])->get();
                $cooperations = $cooperations->merge($coop);
            }
            $cooperations_mou = Cooperation::where('coop_type','MOU')->whereBetween('end_date', [$date1, $date2])->where('status','AC')->get();
            $cooperations = $cooperations->merge($cooperations_mou);
        }

        $data = [];
        $i = 0;
        foreach ($cooperations as $cooperation)
        {
            $partner = $cooperation->partner()->first();
            $area = "";
            if(!empty($cooperation->area_of_coop)){
                $area = $cooperation->areaCoop()->first();
                $area = $area->area_coop;
            }
            if ($cooperation->coop_type == 'MOA')
            {
                $mou_coop = Cooperation::find($cooperation->cooperation_id);

                if(!$mou_coop){
                    $cooperation->form_of_coop = $mou_coop['form_of_coop'];
                    $partner = "";
                }else{
                    $cooperation->form_of_coop = $mou_coop->form_of_coop;
                    $partner = $mou_coop->partner()->first();
                }
            } elseif ($cooperation->coop_type == 'ADDENDUM')
            {
                $prev_coop = Cooperation::find($cooperation->cooperation_id);
                if ($prev_coop->coop_type == 'MOA')
                {
                    $mou_coop = Cooperation::find($prev_coop->cooperation_id);
                    $cooperation->form_of_coop = $mou_coop->form_of_coop;
                    $partner = $mou_coop->partner()->first();
                }
            }

            $data['data'][$i][0] = $cooperation->id;
            $data['data'][$i][1] = $i + 1;
            $data['data'][$i][2] = $cooperation->subject_of_coop;
            $data['data'][$i][3] = $area;
            if(!empty($partner)){
                $data['data'][$i][4] = $partner->name;
            }else{
                $data['data'][$i][4] = "";
            }
            $data['data'][$i][5] = $cooperation->coop_type;
            $data['data'][$i][6] = $cooperation->form_of_coop;
            $data['data'][$i][7] = date('d F Y', strtotime($cooperation->end_date));
            $data['data'][$i][8] = '<a href="display?id='.$cooperation->id.'" data-toggle="tooltip" data-placement="top" title="Lihat"> <button class="btn btn-theme btn-sm rounded coop-view-btn"><i class="fa fa-eye" style="color:white;"></i></button></a>';
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

    public function getAjaxCoopApprove()
    {
        $simsdm = new Simsdm();
        $cooperations = Cooperation::where('status','SB')->where('coop_type','!=','MOU')->get();

        $data = [];
        $i = 0;
        foreach ($cooperations as $cooperation)
        {
            $partner = $cooperation->partner()->first();
            if ($cooperation->coop_type == 'MOA')
            {
                $mou_coop = Cooperation::find($cooperation->cooperation_id);
                $cooperation->form_of_coop = $mou_coop->form_of_coop;
                $partner = $mou_coop->partner()->first();
            } elseif ($cooperation->coop_type == 'ADDENDUM')
            {
                $prev_coop = Cooperation::find($cooperation->cooperation_id);
                if ($prev_coop->coop_type == 'MOA')
                {
                    $mou_coop = Cooperation::find($prev_coop->cooperation_id);
                    $cooperation->form_of_coop = $mou_coop->form_of_coop;
                    $partner = $mou_coop->partner()->first();
                }
            }

            $l_units = $simsdm->unitAll();

            foreach ($l_units as $key=>$un){
                if (is_array($l_units) && in_array($cooperation->unit, $un)){
                    $unit = $un['name'];
                }
            }
            $area = "";
            if(!empty($cooperation->area_of_coop)){
                $area = $cooperation->areaCoop()->first();
                $area = $area->area_coop;
            }

            $data['data'][$i][0] = $i + 1;
            $data['data'][$i][1] = $cooperation->subject_of_coop;
            $data['data'][$i][2] = $area;
            $data['data'][$i][3] = $partner->name;
            $data['data'][$i][4] = $unit;
            $data['data'][$i][5] = date('d F Y', strtotime($cooperation->end_date));
            $data['data'][$i][6] = '<a href="approve?id='.$cooperation->id.'" data-toggle="tooltip" data-placement="top" title="Approve"> <button class="btn btn-theme btn-xs rounded coop-view-btn"><i class="fa fa-check-square-o" style="color:white;"></i></button></a>';
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

    public function downloadDocument()
    {
        $id = Input::get('id');
        $cooperation = Cooperation::find($id);

        if (is_null($id)|| empty($cooperation) || empty($cooperation->file_name_ori))
        {
            return abort('404');
        }

        if (! is_null($cooperation))
        {
            if ($cooperation->coop_type == 'MOU')
            {
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            } elseif ($cooperation->coop_type == 'MOA')
            {
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            }elseif ($cooperation->coop_type == 'SPK')
            {
                $path = Storage::url('upload/' . 'SPK/' . $cooperation->id);
            }elseif ($cooperation->coop_type == 'ADDENDUM')
            {
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);
            }
            $path = storage_path() . '/app' . $path . '/' . $cooperation->file_name;

            if(!File::exists($path)) {
                return abort('404');
            }
        }

        return response()->download($path, $cooperation->file_name_ori);
    }

    public function isHavingRelation()
    {
        $data = [];
        $id = Input::get('id');
        if (is_null($id))
        {
            $data['messages'] = 'Input parameter not found';
            $data = json_encode($data, JSON_PRETTY_PRINT);

            return response($data, 404)->header('Content-Type', 'application/json');
        }

        $cooperation = Cooperation::find($id);
        if (! is_null($cooperation))
        {
            if ($cooperation->coop_type == 'MOA' ||
                $cooperation->coop_type == 'ADDENDUM'
            )
            {
                $data['iTotalRecords'] = 1;
            } else
            {
                $find = Cooperation::where('cooperation_id', $cooperation->id)->first();
                if (! is_null($find))
                    $data['iTotalRecords'] = 1;
                else
                    $data['iTotalRecords'] = 0;
            }
        }

        if ($data['iTotalRecords'] == 1)
        {

        }

        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getAjaxDocument()
    {
        $id = Input::get('id');
        if (is_null($id))
        {
            $data['messages'] = 'Input parameter not found';
            $data = json_encode($data, JSON_PRETTY_PRINT);

            return response($data, 404)->header('Content-Type', 'application/json');
        }

        $cooperation = Cooperation::find($id);
        if (! is_null($cooperation))
        {
            if ($cooperation->coop_type == 'MOU')
            {
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            } elseif ($cooperation->coop_type == 'MOA')
            {
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            }
            $path = $path . '/' . $cooperation->file_name;
        }

        return $path;
    }

    public function getAjaxCoopDetail()
    {
        $id = Input::get("id");
        $cooperation = Cooperation::find($id);
        if (is_null($cooperation))
        {
            return abort('404');
        }

        if ($cooperation->coop_type == 'MOA' || $cooperation->coop_type == 'SPK')
        {
            $mou_coop = Cooperation::find($cooperation->cooperation_id);
            $cooperation->partner_id = $mou_coop->partner()->first()->id;
            $cooperation->partner_name = $mou_coop->partner()->first()->name;
            $coop_items = $cooperation->coopItem()->get();
            $cooperation->coop_items = $coop_items;
        } else
        {
            $cooperation->partner_name = $cooperation->partner()->first()->name;
        }

        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));

        $data = json_encode($cooperation, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getStudyProgram()
    {
        $input = Input::get('faculty');

        $simsdm = new Simsdm();
        $study_programs = $simsdm->studyProgram($input);

        $data = json_encode($study_programs, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');

    }

    private function moveCorresponding($input)
    {
        if (!isset($input['id']))
            $ret = new Cooperation();
        else
            $ret = Cooperation::find($input['id']);

        if ((isset($input['coop_type']) && $input['coop_type'] == 'MOU') ||
            ($ret->coop_type == 'MOU')
        )
        {
            foreach ($input as $key => $item)
            {
                if ($key != '_method' && $key != '_token' && $key != 'file_name_ori' && $key!='approve')
                    $ret[$key] = $item;
            }
        } elseif ($input['coop_type'] == 'MOA' || $input['coop_type'] == 'SPK')
        {
            foreach ($input as $key => $item)
            {
                if ($key != '_method' && $key != '_token' && $key != 'file_name_ori' &&
                    $key != 'item_name' && $key != 'item_quantity' && $key != 'item_uom' &&
                    $key != 'item_total_amount' && $key != 'item_annotation' &&
                    $key != 'is_sub_unit' && $key!='approve'
                )
                    $ret[$key] = $item;
            }
        } else
        {
            $relation_coop = Cooperation::find($input['cooperation_id']);
            if ($relation_coop->coop_type == 'MOU')
            {
                foreach ($input as $key => $item)
                {
                    if ($key != '_method' && $key != '_token' && $key != 'file_name_ori' &&
                        $key != 'addendum_type' && $key!='approve'
                    )
                        $ret[$key] = $item;
                }
            } else
            {
                foreach ($input as $key => $item)
                {
                    if ($key != '_method' && $key != '_token' && $key != 'file_name_ori' &&
                        $key != 'item_name' && $key != 'item_quantity' && $key != 'item_uom' &&
                        $key != 'item_total_amount' && $key != 'item_annotation' &&
                        $key != 'addendum_type' && $key!='approve'
                    )
                        $ret[$key] = $item;
                }
            }
        }

        return $ret;
    }

    private function getCoopRelation($id)
    {
        $cooperation = Cooperation::find($id);
        if ($cooperation->coop_type == "MOU")
        {
            $mou_coop = $cooperation; //MOU
        } elseif ($cooperation->coop_type == "MOA" || $cooperation->coop_type == "SPK")
        {
            $mou_coop = Cooperation::find($cooperation->cooperation_id); //MOU
        } elseif ($cooperation->coop_type == "ADDENDUM")
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
            if ($prev_coop->coop_type == "MOU")
            {
                $mou_coop = $prev_coop; //MOU
            } elseif ($prev_coop->coop_type == "MOA" || $prev_coop->coop_type == "SPK")
            {
                $mou_coop = Cooperation::find($prev_coop->cooperation_id); //MOU
            }
        }

        if(!is_null($mou_coop)){
            $addendum_coops = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $mou_coop->id)->get(); //ADDENDUM MOU
            $user_auth = $this->getUserAuth();

            $spk_coops = new Collection();
            foreach ($user_auth as $user){
                if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                    $spk_coops = Cooperation::where("coop_type", "SPK")->where("cooperation_id", $mou_coop->id)->get(); //SPK
                }else{
                    $spk_coop = Cooperation::where('coop_type', 'SPK')->where("cooperation_id", $mou_coop->id)->where('unit',$user->unit)->get();

                    if($spk_coop){
                        $merged = $spk_coops->merge($spk_coop);
                        $spk_coops = $merged;
                    }

                    $spk_coop_sub = Cooperation::where('coop_type', 'SPK')->where('sub_unit',$user->sub_unit)->get();
                    if($spk_coop_sub){
                        $spk_coops->merge($spk_coop_sub);
                    }
                }
            }

            $spk_addendum_coops = new Collection(); // ADDENDUM MOA
            foreach ($spk_coops as $spk_coop)
            {
                foreach ($user_auth as $user){
                    if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                        $spk_addendums = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $spk_coop->id)->get();
                        foreach ($spk_addendums as $spk_addendum)
                        {
                            $spk_addendum_coops->add($spk_addendum);
                        }
                    }else{
                        $spk_addendums_unit = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $spk_coop->id)->where('unit',$user->unit)->get();
                        if($spk_addendums_unit){
                            foreach ($spk_addendums_unit as $spk_addendum)
                            {
                                $spk_addendum_coops->add($spk_addendum);
                            }
                        }

                        $spk_addendums_sub_unit = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $spk_coop->id)->where('sub_unit',$user->sub_unit)->get();
                        if($spk_addendums_sub_unit){
                            foreach ($spk_addendums_sub_unit as $spk_addendum)
                            {
                                $spk_addendum_coops->add($spk_addendum);
                            }
                        }
                    }
                }
            }

            $moa_coops = new Collection();
            foreach ($user_auth as $user){
                if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                    $moa_coops = Cooperation::where("coop_type", "MOA")->where("cooperation_id", $mou_coop->id)->get(); //MOA
                }else{
                    $moa_coop = Cooperation::where('coop_type', 'MOA')->where("cooperation_id", $mou_coop->id)->where('unit',$user->unit)->get();

                    if($moa_coop){
                        $merged = $moa_coops->merge($moa_coop);
                        $moa_coops = $merged;
                    }

                    $moa_coop_sub = Cooperation::where('coop_type', 'MOA')->where('sub_unit',$user->sub_unit)->get();
                    if($moa_coop_sub){
                        $moa_coops->merge($moa_coop_sub);
                    }
                }
            }

            $moa_addendum_coops = new Collection(); // ADDENDUM MOA
            foreach ($moa_coops as $moa_coop)
            {
                foreach ($user_auth as $user){
                    if($user->auth_type=='SU' || $user->auth_type=='SAU'){
                        $moa_addendums = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $moa_coop->id)->get();
                        foreach ($moa_addendums as $moa_addendum)
                        {
                            $moa_addendum_coops->add($moa_addendum);
                        }
                    }else{
                        $moa_addendums_unit = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $moa_coop->id)->where('unit',$user->unit)->get();
                        if($moa_addendums_unit){
                            foreach ($moa_addendums_unit as $moa_addendum)
                            {
                                $moa_addendum_coops->add($moa_addendum);
                            }
                        }

                        $moa_addendums_sub_unit = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $moa_coop->id)->where('sub_unit',$user->sub_unit)->get();
                        if($moa_addendums_sub_unit){
                            foreach ($moa_addendums_sub_unit as $moa_addendum)
                            {
                                $moa_addendum_coops->add($moa_addendum);
                            }
                        }
                    }
                }
            }

            $ret = [];
            $ret[0]['level'] = 1;
            $ret[0]['id'] = $mou_coop->id;
            $ret[0]['subject_of_coop'] = $mou_coop->subject_of_coop;
            $ret[0]['coop_type'] = $mou_coop->coop_type;
            $i = 1;

            foreach ($addendum_coops as $item)
            {
                $ret[$i]['level'] = 2;
                $ret[$i]['id'] = $item->id;
                $ret[$i]['subject_of_coop'] = $item->subject_of_coop;
                $ret[$i]['parent_id'] = $item->cooperation_id;
                $ret[$i]['coop_type'] = $item->coop_type;
                $i++;
            }

            foreach ($spk_coops as $item)
            {
                $ret[$i]['level'] = 3;
                $ret[$i]['id'] = $item->id;
                $ret[$i]['subject_of_coop'] = $item->subject_of_coop;
                $ret[$i]['parent_id'] = $item->cooperation_id;
                $ret[$i]['coop_type'] = $item->coop_type;
                $i++;
            }

            foreach ($spk_addendum_coops as $item)
            {
                $ret[$i]['level'] = 4;
                $ret[$i]['id'] = $item->id;
                $ret[$i]['subject_of_coop'] = $item->subject_of_coop;
                $ret[$i]['parent_id'] = $item->cooperation_id;
                $ret[$i]['coop_type'] = $item->coop_type;
                $i++;
            }

            foreach ($moa_coops as $item)
            {
                $ret[$i]['level'] = 3;
                $ret[$i]['id'] = $item->id;
                $ret[$i]['subject_of_coop'] = $item->subject_of_coop;
                $ret[$i]['parent_id'] = $item->cooperation_id;
                $ret[$i]['coop_type'] = $item->coop_type;
                $i++;
            }

            foreach ($moa_addendum_coops as $item)
            {
                $ret[$i]['level'] = 4;
                $ret[$i]['id'] = $item->id;
                $ret[$i]['subject_of_coop'] = $item->subject_of_coop;
                $ret[$i]['parent_id'] = $item->cooperation_id;
                $ret[$i]['coop_type'] = $item->coop_type;
                $i++;
            }

            return $ret;
        }

    }
}