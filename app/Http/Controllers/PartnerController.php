<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePartnerRequest;
use App\Http\Requests\StorePartnerRequest;
use App\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use View;

class PartnerController extends MainController {

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
        $page_title = 'Instansi Partner';

        return view('partner.partner-list', compact('page_title'));
    }

    public function create()
    {
        $page_title = 'Instansi Partner';
        $action_url = 'partners/create';
        $upd_mode = 'create';

        return view('partner.partner-detail', compact(
            'page_title',
            'upd_mode',
            'action_url'
        ));
    }

    public function store(StorePartnerRequest $request)
    {
//        $this->authorize('create', Partner::class);

        $input = Input::all();

        $partner = new Partner();
        $partner->fill($input);
        $partner->created_by = $this->user_info['username'];
        $partner->save();

        $request->session()->flash('alert-success', 'Instansi Partner berhasil ditambah!');

        return redirect()->intended('partners');
    }

    public function edit()
    {
        $input = Input::all();

        if(!isset($input['id']))
        {
            return abort('404');
        }

        $partner = Partner::find($input['id']);
        $page_title = 'Instansi Partner';
        $upd_mode = 'edit';
        $action_url = 'partners/edit';

        return view('partner.partner-detail', compact(
            'partner',
            'upd_mode',
            'page_title',
            'action_url'
        ));
    }

    public function update(StorePartnerRequest $request)
    {
        $input = Input::all();

        $partner = Partner::find($input['id']);
        $partner->fill($input);
        $partner->updated_by = $this->user_info['username'];
        $partner->save();

        $request->session()->flash('alert-success', 'Instansi Partner berhasil diupdate!');

        return redirect()->intended('partners');
    }

    public function destroy(DeletePartnerRequest $request)
    {
        Partner::destroy($request->id);

        $request->session()->flash('alert-success', 'Instansi Partner berhasil dihapus!');

        return redirect()->intended('partners');
    }

    public function getAjax()
    {
        $partners = Partner::all();

        $data = [];

        $i = 0;
        foreach ($partners as $partner)
        {
            $data['data'][$i][0] = $partner->id;
            $data['data'][$i][1] = $i + 1;
            $data['data'][$i][2] = $partner->name;
            $data['data'][$i][3] = $partner->address;
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
