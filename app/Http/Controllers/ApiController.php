<?php

namespace App\Http\Controllers;

use App\Cooperation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class ApiController extends MainController
{
    public function __construct()
    {
        $this->middleware('is_auth');
        parent::__construct();
    }

    public function searchCoop()
    {
        $query = Input::get('query');
        $limit = Input::get('limit');
        $cooperations = Cooperation::all([
            'id',
            'partner_id',
            'coop_type',
            'area_of_coop',
            'sign_date',
            'end_date',
            'form_of_coop',
            'usu_doc_no',
            'partner_doc_no',
            'implementation',
            'unit',
            'contract_amount',
        ]);
        if(!is_null($limit))
        {
            $cooperations = $cooperations->take($limit);
        }
        $results = [];

        $coop_items = new Collection();
        $i = 0;
        foreach ($cooperations as $cooperation)
        {
            $results[$i] = $cooperation;
            $results[$i]['item'] = $cooperation->coopItem()->get([
                'item_name',
                'item_quantity',
                'item_uom',
                'item_total_amount',
                'item_annotation',
            ]);
            $i++;
        }

        $results = json_encode($results, JSON_PRETTY_PRINT);

        return response($results, 200)->header('Content-Type', 'application/json');
    }
}
