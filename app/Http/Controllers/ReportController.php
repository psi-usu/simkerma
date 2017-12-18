<?php

namespace App\Http\Controllers;

use App\AreasCoop;
use App\Cooperation;
use App\Partner;
use App\Simsdm;
use App\User;
use DB;
use Excel;
use View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use parinpan\fanjwt\libs\JWTAuth;

class ReportController extends MainController
{
    public function __construct()
    {
        $this->middleware('is_auth')->except('index');

        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/select2/select2.min.css');

        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/moment/min/moment.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'js/pages/blankon.form.picker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');

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
        } else{
            $user = new User();
            $user->username = $login->payload->identity;
            $user->user_id = $login->payload->user_id;
            Auth::login($user);

            $this->setUserInfo();

            $page_title = 'Filter';
            $partners = Partner::all();
            $areas = AreasCoop::get();
            $simsdm = new Simsdm();
            $units = $simsdm->unitAll();

            return view('cooperation.cooperation-report', compact(
                'page_title',
                'partners',
                'areas',
                'units'
            ));
        }
    }

    public function getReport(Request $request)
    {
        $simsdm = new Simsdm();
        $data = [];
        $i = 0;

        $date1 = str_replace('/', '-', $request->sign_date1);
        $sign_date1 = date('Y-m-d', strtotime($date1));

        $date2 = str_replace('/', '-', $request->sign_date2);
        $sign_date2 = date('Y-m-d', strtotime($date2));

        if($request->coop_type=='all' && $request->partner=='all')
        {
            $coops  = Cooperation::where('status','AC')->whereBetween('sign_date', [$sign_date1, $sign_date2])->take(5)->get();
        }elseif ($request->coop_type=='all' && $request->partner!= 'all'){
            $coops  = Cooperation::where('status','AC')->where('partner_id', $request->partner)->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }elseif ($request->coop_type!='all' && $request->partner == 'all'){
            $coops  = Cooperation::where('status','AC')->where('coop_type', $request->coop_type)->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }
        else{
            $coops  = Cooperation::where('status','AC')->where('partner_id', $request->partner)->where('coop_type', $request->coop_type)->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }

        foreach ($coops as $coop){
            $partner = $coop->partner()->first();

            if ($coop->coop_type == 'MOA' || $coop->coop_type == 'SPK')
            {
                $mou_coop = Cooperation::find($coop->cooperation_id);
                if(!$mou_coop){
                    $partner = "";
                }else{
                    $partner = $mou_coop->partner()->first();
                }
            }

            if($coop->coop_type == 'ADDENDUM'){
                $moa_coop = Cooperation::find($coop->cooperation_id);
                $mou_coop = Cooperation::find($moa_coop->cooperation_id);
                if(!$mou_coop){
                    $partner = "";
                }else{
                    $partner = $mou_coop->partner()->first();
                }
            }

            $unit = "";
            foreach ($simsdm->unitAll() as $units){
                if($units['code']==$coop->unit){
                    $unit = $units['name'];
                }
            }

            $data[$i]['no'] = $i + 1;
            if(!empty($partner)){
                $data[$i]['partner']= $partner->name;
            }else{
                $data[$i]['partner']= "";
            }
            $data[$i]['coop_type'] = $coop->coop_type;
            $data[$i]['subject_of_coop'] = $coop->subject_of_coop;
            $data[$i]['unit'] = $unit;
            $data[$i]['sign_date'] = date('d-m-Y', strtotime($coop->sign_date));
            $data[$i]['end_date'] = date('d-m-Y', strtotime($coop->end_date));
            $i++;
        }

        $data = json_encode($data, JSON_PRETTY_PRINT);
        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function downloadExcel()
    {
        $simsdm = new Simsdm();
        $data = [];
        $i = 0;

        $date1 = str_replace('/', '-', Input::get('sign_date1'));
        $sign_date1 = date('Y-m-d', strtotime($date1));
        $date2 = str_replace('/', '-', Input::get('sign_date2'));
        $sign_date2 = date('Y-m-d', strtotime($date2));

        if(Input::get('coop_type')=='all' && Input::get('partner')=='all'){
            $coops  = Cooperation::where('status','AC')->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }elseif (Input::get('coop_type')=='all' && Input::get('partner')!='all'){
            $coops  = Cooperation::where('status','AC')->where('partner_id',Input::get('partner'))->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }elseif (Input::get('coop_type')!='all' && Input::get('partner')=='all'){
            $coops  = Cooperation::where('status','AC')->where('coop_type', Input::get('coop_type'))->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        } else{
            $coops  = Cooperation::where('status','AC')->where('partner_id', Input::get('partner'))->where('coop_type', Input::get('coop_type'))->whereBetween('sign_date', [$sign_date1, $sign_date2])->get();
        }

        foreach ($coops as $coop){
            $partner = $coop->partner()->first();

            if ($coop->coop_type == 'MOA' || $coop->coop_type == 'SPK'){
                $mou_coop = Cooperation::find($coop->cooperation_id);
                if(!$mou_coop){
                    $partner = "";
                }else{
                    $partner = $mou_coop->partner()->first();
                }
            }

            if($coop->coop_type == 'ADDENDUM'){
                $moa_coop = Cooperation::find($coop->cooperation_id);
                $mou_coop = Cooperation::find($moa_coop->cooperation_id);
                if(!$mou_coop){
                    $partner = "";
                }else{
                    $partner = $mou_coop->partner()->first();
                }
            }

            $unit = "";
            foreach ($simsdm->unitAll() as $units){
                if($units['code']==$coop->unit){
                    $unit = $units['name'];
                }
            }

            $data[$i]['No'] = $i + 1;
            if(!empty($partner)){
                $data[$i]['Partner']= $partner->name;
            }else{
                $data[$i]['Partner']= "";
            }
            $data[$i]['Jenis Kerja Sama'] = $coop->coop_type;
            $data[$i]['Subjek Kerjasama'] = $coop->subject_of_coop;
            $data[$i]['Unit'] = $unit;
            $data[$i]['Tanggal Tanda Tangan'] = date('d-m-Y', strtotime($coop->sign_date));
            $data[$i]['Tanggal Berakhir Kerjasama'] = date('d-m-Y', strtotime($coop->end_date));

            $i++;
        }
        $date = date('d-m-Y');
        return Excel::create('Laporan_Kerjasama_'.$date, function($excel) use ($data) {

            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->row(1, array('DAFTAR NASKAH KERJASAMA UNIVERSITAS SUMATERA UTARA'));
                $sheet->mergeCells('A1:G1');
                $sheet->row(1, function ($row) {
                    $row->setFontSize(14);
                    $row->setAlignment('center');
                });

                $sheet->appendRow(array_keys($data[0]));
                $sheet->cell('A', function($cell) {
                    $cell->setAlignment('center');
                });

                foreach ($data as $kerma) {
                    $sheet->appendRow($kerma);
                }
            });
        })->download('xlsx');
    }
}
