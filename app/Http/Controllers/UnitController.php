<?php

namespace App\Http\Controllers;

use App\Simsdm;
use View;
use Illuminate\Http\Request;

class UnitController extends MainController
{
    public function __construct()
    {
        $this->middleware('is_auth');

        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/select2/select2.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/dataTables.bootstrap.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/datatables.responsive.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/select2/select2.full.min.js');

        array_push($this->js['scripts'], 'js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
    }

    public function index()
    {
        $page_title = 'Daftar Unit';

        return view('unit.unit-list', compact('page_title'));
    }

    public function getAjax()
    {
        $simsdm = new Simsdm();
        $faculties = $simsdm->facultyAll();
        $units = $simsdm->unitAll();
        $data = [];

        $i = 0;
        foreach ($faculties as $faculty)
        {
            $data['data'][$i][0] = $i + 1;
            $data['data'][$i][1] = $faculty['code'];
            $data['data'][$i][2] = $faculty['name'];
            $i++;
        }
        foreach ($units as $unit)
        {
            $data['data'][$i][0] = $i + 1;
            $data['data'][$i][1] = $unit['code'];
            $data['data'][$i][2] = $unit['name'];
            $i++;
        }

        $count_data = count($data);
        if ($count_data == 0)
        {
            $data['data'] = [];
        }else{
            $count_data = count($data['data']);
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }
}