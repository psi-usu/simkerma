<?php

namespace App\Http\Controllers;

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
            $login->payload->identity = env('LOGIN_USERNAME');
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
            Auth::login($user);

            $this->setUserInfo();
            $page_title = 'Filter';
            $partners = Partner::all();

            return view('cooperation.cooperation-report', compact(
                'page_title',
                'partners'
            ));
        }
    }

    public function getReport(Request $request)
    {
        $simsdm = new Simsdm();
        $data = [];
        $i = 0;

        $request->sign_date = explode(' - ',$request->sign_date);

        $date1 = str_replace('/', '-', $request->sign_date[0]);
        $sign_date1 = date('Y-m-d', strtotime($date1));

        $date2 = str_replace('/', '-', $request->sign_date[1]);
        $sign_date2 = date('Y-m-d', strtotime($date2));

        $request->end_date = explode(' - ',$request->end_date);
        $end_date1 = date('Y-m-d', strtotime($request->end_date[0]));
        $end_date2 = date('Y-m-d', strtotime($request->end_date[1]));

        if($request->coop_type=='all' && $request->partner=='all')
        {
            $coops  = Cooperation::whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])->get();
        }elseif ($request->coop_type=='all' && $request->partner!= 'all'){
            $coops  = Cooperation::where('partner_id', $request->partner)->whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }elseif ($request->coop_type!='all' && $request->partner == 'all'){
            $coops  = Cooperation::where('coop_type', $request->coop_type)->whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }
        else{
            $coops  = Cooperation::where('partner_id', $request->partner)->where('coop_type', $request->coop_type)->whereBetween('sign_date', [$sign_date1, $sign_date2])
                ->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }

        foreach ($coops as $coop){
            $partner = $coop->partner()->first();

            if ($coop->coop_type == 'MOA')
            {
                $mou_coop = Cooperation::find($coop->cooperation_id);
                $partner = $mou_coop->partner()->first();
            }

            $unit = null;
            foreach ($simsdm->unitAll() as $units){
                if($units['code']==$coop->unit){
                    $unit = $units['name'];
                }
            }

            $data[$i]['no'] = $i + 1;
            $data[$i]['partner'] = $partner->name;
            $data[$i]['coop_type'] = $coop->coop_type;
            $data[$i]['area_of_coop'] = $coop->area_of_coop;
            $data[$i]['unit'] = $unit;
            $data[$i]['sign_date'] = date('d-m-Y', strtotime($coop->sign_date));
            $data[$i]['end_date'] = date('d-m-Y', strtotime($coop->end_date));
            $i++;
        }

        $data = json_encode($data, JSON_PRETTY_PRINT);
        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function importExport()
    {
        return view('importExport');
    }
    public function downloadExcel()
    {
        $simsdm = new Simsdm();
        $data = [];
        $i = 0;

        $sign_date = explode(' - ',Input::get('sign_date'));

        $date1 = str_replace('/', '-', $sign_date[0]);
        $sign_date1 = date('Y-m-d', strtotime($date1));
        $date2 = str_replace('/', '-', $sign_date[1]);
        $sign_date2 = date('Y-m-d', strtotime($date2));

        $end_date = explode(' - ', Input::get('end_date'));

        $date3 = str_replace('/', '-', $end_date[0]);
        $end_date1 = date('Y-m-d', strtotime($date3));
        $date4 = str_replace('/', '-', $end_date[1]);
        $end_date2 = date('Y-m-d', strtotime($date4));

        if(Input::get('coop_type')=='all' && Input::get('partner')=='all')
        {
            $coops  = Cooperation::whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])->get();
        }elseif (Input::get('coop_type')=='all' && Input::get('partner')!='all'){
            $coops  = Cooperation::where('partner_id',Input::get('partner'))->whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }elseif (Input::get('coop_type')!='all' && Input::get('partner')=='all'){
            $coops  = Cooperation::where('coop_type', Input::get('coop_type'))->whereBetween('sign_date', [$sign_date1, $sign_date2])->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }
        else{
            $coops  = Cooperation::where('partner_id', Input::get('partner'))->where('coop_type', Input::get('coop_type'))->whereBetween('sign_date', [$sign_date1, $sign_date2])
                ->OrwhereBetween('end_date', [$end_date1, $end_date2])
                ->get();
        }

        foreach ($coops as $coop){
            $partner = $coop->partner()->first();

            if ($coop->coop_type == 'MOA')
            {
                $mou_coop = Cooperation::find($coop->cooperation_id);
                $partner = $mou_coop->partner()->first();
            }

            $unit = null;
            foreach ($simsdm->unitAll() as $units){
                if($units['code']==$coop->unit){
                    $unit = $units['name'];
                }
            }

            $data[$i]['No'] = $i + 1;
            $data[$i]['Partner'] = $partner->name;
            $data[$i]['Jenis Kerja Sama'] = $coop->coop_type;
            $data[$i]['Bidang Kerjasama'] = $coop->area_of_coop;
            $data[$i]['Unit'] = $unit;
            $data[$i]['Tanggal Tanda Tangan'] = date('d-m-Y', strtotime($coop->sign_date));
            $data[$i]['Tanggal Berakhir Kerjasama'] = date('d-m-Y', strtotime($coop->end_date));
            if($coop)
            $i++;
        }

        return Excel::create('Laporan Kerjasama', function($excel) use ($data) {

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
        })->download('xls');
    }
}
