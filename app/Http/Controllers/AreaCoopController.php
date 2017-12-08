<?php

namespace App\Http\Controllers;

use App\AreasCoop;
use App\Http\Requests\StoreAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Input;
use View;

class AreaCoopController extends MainController
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
        $page_title = 'Bidang Kerjasama';
        $action_url = 'areas_of_coop/create';

        return view('area.area-list', compact('page_title', 'action_url'));
    }

    public function store(StoreAreaRequest $request)
    {
        $input = Input::all();

        $area = new AreasCoop();
        $area->area_coop = $input['area_coop'];
        $area->created_by = $this->user_info['username'];
        $area->save();

        $request->session()->flash('alert-success', 'Bidang Kerjsama berhasil ditambah!');

        return redirect()->intended('areas_of_coop');
    }

    public function update(StoreAreaRequest $request)
    {
        $input = Input::all();

        $area = AreasCoop::find($input['id']);
        $area->area_coop = $input['area_coop'];
        $area->updated_by = $this->user_info['username'];
        $area->save();

        $request->session()->flash('alert-success', 'Bidang Kerjsama berhasil diubah!');

        return redirect()->intended('areas_of_coop');
    }

    public function destroy()
    {
        $input = Input::all();
        $area = AreasCoop::find($input['id']);
        if(empty($area)){
            return abort('404');
        }else{
            $area->delete();
        }

        session()->flash('alert-success', 'Bidang Kerjasama berhasil dihapus!');

        return redirect()->intended('areas_of_coop');
    }

    public function getAjax()
    {
        $areas = AreasCoop::all();

        $data = [];

        $i = 0;
        foreach ($areas as $area)
        {
            $data['data'][$i][0] = $area->id;
            $data['data'][$i][1] = $i + 1;
            $data['data'][$i][2] = $area->area_coop;
//            $data['data'][$i][3] = '<a id="editBtn" data-toggle="tooltip" data-id1="'.$area->id.'" data-id2="'.$area->area_coop.'" data-placement="top" title="Edit"><button class="btn btn-theme btn-sm rounded edit"><i class="fa fa-pencil" style="color:white;"></i></button></a>';
            $data['data'][$i][3] = '<a data-id1="'.$area->id.'" data-id2="'.$area->area_coop.'" class="btn btn-theme btn-sm rounded edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil" style="color:white;"></i></a>';
            $data['data'][$i][3].= '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>';
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
