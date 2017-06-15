<?php

namespace App\Http\Controllers;

use App\Cooperation;
use App\CoopItem;
use App\CoopType;
use App\Http\Requests\StoreCooperationRequest;
use App\Partner;
use App\Simsdm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use View;

class CooperationController extends MainController {

    public function __construct()
    {
        $this->middleware('auth');

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
        $page_title = 'Kerjasama';

        return view('cooperation.coop-list', compact('page_title'));
    }

    public function soonEndsList()
    {
        return ('cooperation.coop-soon-ends');
    }

    public function create()
    {
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'kartik-v/bootstrap-fileinput/css/fileinput.min.css');

        array_push($this->js['scripts'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');
        array_push($this->js['scripts'], 'kartik-v/bootstrap-fileinput/js/fileinput.min.js');
        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);

        $page_title = "Tambah Kerjasama";
        $upd_mode = 'create';
        $action_url = 'cooperations/create';

        $simsdm = new Simsdm();
        $partners = Partner::all();
        $coop_types = CoopType::all();
        $mou_coops = Cooperation::where('coop_type', 'MOU')->get();
        $moa_coops = Cooperation::where('coop_type', 'MOA')->get();
        $faculties = $simsdm->facultyAll();
        $units = $simsdm->unitAll();
        $coop_items = new Collection();
        $coop_item = new CoopItem();
        $coop_items->add($coop_item);

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'partners',
            'coop_types',
            'mou_coops',
            'moa_coops',
            'faculties',
            'units',
            'coop_items'
        ));
    }

    public function store(StoreCooperationRequest $request)
    {
        $this->authorize('create', Cooperation::class);

        $input = Input::all();

        DB::transaction(function () use ($input, $request)
        {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['created_by'] = Auth::user()->username;
            $cooperation['sign_date'] = date('Y-m-d', strtotime($cooperation['sign_date']));
            $cooperation['end_date'] = date('Y-m-d', strtotime($cooperation['end_date']));
            $cooperation->status = 'submit';
            $cooperation->contract_amount = 0;

            if ($cooperation->coop_type == 'ADDENDUM')
                $relation_coop = Cooperation::find($input['cooperation_id']);

            if ($cooperation->coop_type == 'MOA' ||
                ($cooperation->coop_type == 'ADDENDUM' && $relation_coop->coop_type == 'MOA')
            )
            {
                if (isset($input['item_name']))
                {
                    $coop_items = new Collection();
                    foreach ($input['item_name'] as $key => $item)
                    {
                        $coop_item = new CoopItem();
                        $coop_item->item = $key;
                        $coop_item->item_name = $input['item_name'][$key];
                        $coop_item->item_quantity = $input['item_quantity'][$key];
                        $coop_item->item_uom = $input['item_uom'][$key];
                        $coop_item->item_total_amount = str_replace(',', '', $input['item_total_amount'][$key]);
                        $coop_item->item_annotation = $input['item_annotation'][$key];
                        $cooperation->contract_amount += $coop_item->item_total_amount;
                        $coop_items->add($coop_item);
                    }
                }
            }

            $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
            $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();

            $cooperation->save();

            if (isset($coop_items))
            {
                $cooperation->coopItem()->saveMany($coop_items);
            }

            if ($cooperation->coop_type == 'MOU')
                $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
            elseif ($cooperation->coop_type == 'MOA')
                $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
            else
                $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

            if (! is_null($request->file('file_name_ori')))
            {
                $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
            }
        });

        $request->session()->flash('alert-success', 'Kerjasama berhasil dibuat');

        return redirect()->intended('/');
    }

    public function update(StoreCooperationRequest $request)
    {
        $this->authorize('update', Cooperation::class);

        $input = Input::all();

        DB::transaction(function () use ($input, $request)
        {
            $cooperation = $this->moveCorresponding($input);
            $cooperation['updated_by'] = Auth::user()->username;
            $cooperation['sign_date'] = date('Y-m-d', strtotime($cooperation['sign_date']));
            $cooperation['end_date'] = date('Y-m-d', strtotime($cooperation['end_date']));

            if (! is_null($request->file('file_name_ori')))
            {
                $cooperation->file_name_ori = $request->file('file_name_ori')->getClientOriginalName();
                $cooperation->file_name = sha1($cooperation->file_name_ori . Carbon::now()->toDateTimeString()) . '.' . $request->file('file_name_ori')->getClientOriginalExtension();
                if ($cooperation->coop_type == 'MOU')
                    $path = Storage::url('upload/' . 'MOU/' . $cooperation->id);
                elseif ($cooperation->coop_type == 'MOA')
                    $path = Storage::url('upload/' . 'MOA/' . $cooperation->id);
                else
                    $path = Storage::url('upload/' . 'ADDENDUM/' . $cooperation->id);

                Storage::delete($path . '/' . $cooperation->file_name);
                $request->file('file_name_ori')->storeAs($path, $cooperation->file_name);
            }
            $cooperation->save();
        });

        $request->session()->flash('alert-success', 'Kerjasama berhasil di-update');

        return redirect()->intended('/');
    }

    public function display()
    {
        $input = Input::all();
        if (! isset($input['id']))
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

        $simsdm = new Simsdm();
        $cooperation = Cooperation::find($input['id']);
        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));
        $partners = Partner::all();
        $coop_types = CoopType::all();
        $faculties = $simsdm->facultyAll();
        $units = $simsdm->unitAll();
        $mou_coops = Cooperation::where('coop_type', 'MOU')->get();
        $moa_coops = Cooperation::where('coop_type', 'MOA')->get();
        $coop_items = $cooperation->coopItem()->get();
        if ($cooperation->coop_type == 'ADDENDUM')
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
        }
        $coop_tree_relations = $this->getCoopRelation($input['id']);

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'cooperation',
            'partners',
            'coop_types',
            'faculties',
            'units',
            'mou_coops',
            'moa_coops',
            'prev_coop',
            'coop_items',
            'coop_tree_relations',
            'disabled'
        ));
    }

    public function edit()
    {
        $input = Input::all();
        if (! isset($input['id']))
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

        $page_title = "Edit Kerjasama";
        $upd_mode = 'edit';
        $action_url = 'cooperations/edit';

        $cooperation = Cooperation::find($input['id']);
        $cooperation->sign_date = date('d-m-Y', strtotime($cooperation->sign_date));
        $cooperation->end_date = date('d-m-Y', strtotime($cooperation->end_date));
        $coop_relations = Cooperation::where('cooperation_id', $cooperation->id)->get();
        $partners = Partner::all();
        $coop_types = CoopType::all();

        $disabled = null;
        if (! $coop_relations->isEmpty())
            $disabled = "disabled";

        return view('cooperation.coop-detail', compact(
            'page_title',
            'upd_mode',
            'action_url',
            'cooperation',
            'coop_relations',
            'partners',
            'coop_types',
            'disabled'
        ));
    }

    public function getAjax()
    {
        $cooperations = Cooperation::all();

        $data = [];

        $i = 0;
        foreach ($cooperations as $cooperation)
        {
            $partner = $cooperation->partner()->first();
            $coop_type = $cooperation->coopType()->first();
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
            $data['data'][$i][0] = $cooperation->id;
            $data['data'][$i][1] = $i + 1;
            $data['data'][$i][2] = $cooperation->area_of_coop;
            $data['data'][$i][3] = $partner->name;
            $data['data'][$i][4] = $coop_type->type;
            $data['data'][$i][5] = $cooperation->form_of_coop;
            $data['data'][$i][6] = $cooperation->end_date;

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
        if (is_null($id))
        {
            return abort('404');
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
            $path = storage_path() . '/app' . $path . '/' . $cooperation->file_name;
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

        if ($cooperation->coop_type == 'MOA')
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
        if (isset($input['coop_type']))
            $ret = new Cooperation();
        else
            $ret = Cooperation::find($input['id']);

        if ((isset($input['coop_type']) && $input['coop_type'] == 'MOU') ||
            ($ret->coop_type == 'MOU')
        )
        {
            foreach ($input as $key => $item)
            {
                if ($key != '_method' && $key != '_token' && $key != 'file_name_ori')
                    $ret[$key] = $item;
            }
        } elseif ($input['coop_type'] == 'MOA')
        {
            foreach ($input as $key => $item)
            {
                if ($key != '_method' && $key != '_token' && $key != 'file_name_ori' &&
                    $key != 'item_name' && $key != 'item_quantity' && $key != 'item_uom' &&
                    $key != 'item_total_amount' && $key != 'item_annotation' &&
                    $key != 'is_sub_unit'
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
                        $key != 'addendum_type'
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
                        $key != 'addendum_type'
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
        } elseif ($cooperation->coop_type == "MOA")
        {
            $mou_coop = Cooperation::find($cooperation->cooperation_id); //MOU
        } elseif ($cooperation->coop_type == "ADDENDUM")
        {
            $prev_coop = Cooperation::find($cooperation->cooperation_id);
            if ($prev_coop->coop_type == "MOU")
            {
                $mou_coop = $prev_coop; //MOU
            } elseif ($prev_coop->coop_type == "MOA")
            {
                $mou_coop = Cooperation::find($prev_coop->cooperation_id); //MOU
            }
        }
        $addendum_coops = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $mou_coop->id)->get(); //ADDENDUM MOU
        $moa_coops = Cooperation::where("coop_type", "MOA")->where("cooperation_id", $mou_coop->id)->get(); //MOA
        $moa_addendum_coops = new Collection(); // ADDENDUM MOA
        foreach ($moa_coops as $moa_coop)
        {
            $moa_addendums = Cooperation::where("coop_type", "ADDENDUM")->where("cooperation_id", $moa_coop->id)->get();
            foreach ($moa_addendums as $moa_addendum)
            {
                $moa_addendum_coops->add($moa_addendum);
            }
        }

        $ret = [];
        $ret[0]['level'] = 1;
        $ret[0]['id'] = $mou_coop->id;
        $ret[0]['area_of_coop'] = $mou_coop->area_of_coop;
        $ret[0]['coop_type'] = $mou_coop->coop_type;
        $i = 1;
        foreach ($addendum_coops as $item)
        {
            $ret[$i]['level'] = 2;
            $ret[$i]['id'] = $item->id;
            $ret[$i]['area_of_coop'] = $item->area_of_coop;
            $ret[$i]['parent_id'] = $item->cooperation_id;
            $ret[$i]['coop_type'] = $item->coop_type;
            $i++;
        }
        foreach ($moa_coops as $item)
        {
            $ret[$i]['level'] = 3;
            $ret[$i]['id'] = $item->id;
            $ret[$i]['area_of_coop'] = $item->area_of_coop;
            $ret[$i]['parent_id'] = $item->cooperation_id;
            $ret[$i]['coop_type'] = $item->coop_type;
            $i++;
        }
        foreach ($moa_addendum_coops as $item)
        {
            $ret[$i]['level'] = 4;
            $ret[$i]['id'] = $item->id;
            $ret[$i]['area_of_coop'] = $item->area_of_coop;
            $ret[$i]['parent_id'] = $item->cooperation_id;
            $ret[$i]['coop_type'] = $item->coop_type;
            $i++;
        }
        return $ret;
    }
}
