<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Cooperation;
use App\GlobalClass\UserAuth;
use Illuminate\Foundation\Http\FormRequest;

class StoreCooperationRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        if ($this->input('save_temporary') == 1)
//        {
//            return [
//                'coop_type' => 'max:10',
//                'sign_date' => 'date',
//                'end_date'  => 'date',
//                'unit'      => 'max:30',
//            ];
//        } else
//        {
        $rules = [
            'area_of_coop'   => 'required',
            'sign_date'      => 'required|date',
            'end_date'       => 'required|date',
            'usu_doc_no'     => 'required',
            'partner_doc_no' => 'required',
        ];

        $cooperation = Cooperation::find($this->input('cooperation_id'));
        if (! is_null($cooperation) && $this->input('coop_type') == 'ADDENDUM')
        {
            if ($cooperation->coop_type == 'MOA')
            {
                $rules = array_add($rules, 'implementation', 'required');
                $rules = array_add($rules, 'unit', 'required|max:30');
            }

            if ($this->input('coop_type') == 'MOU')
            {
                $rules = array_add($rules, 'partner_id', 'required');
                $rules = array_add($rules, 'form_of_coop', 'required');
            }
        }

        if ($this->input('coop_type') == 'MOA')
        {
            $rules = array_add($rules, 'implementation', 'required');
            $rules = array_add($rules, 'unit', 'required|max:30');
        }

        if ($this->input('coop_type') == 'MOU')
        {
            $rules = array_add($rules, 'partner_id', 'required');
            $rules = array_add($rules, 'form_of_coop', 'required');
        }

        if ($this->input('id') == null)
        {
            $rules = array_add($rules, 'coop_type', 'required|max:10');
            $rules = array_add($rules, 'file_name_ori', 'required|mimes:pdf');
        }

        return $rules;
    }

//    }

    public function messages()
    {
        return [
            'partner_id.required'     => 'Instansi Partner harus diisi',
            'coop_type.required'      => 'Jenis Kerjasama harus diisi',
            'area_of_coop.required'   => 'Bidang Kerjasama harus diisi',
            'sign_date.required'      => 'Tanggal Tanda Tangan harus diisi',
            'end_date.required'       => 'Tanggal Berakhir harus diisi',
            'form_of_coop.required'   => 'Bentuk Kerjasama harus diisi',
            'usu_doc_no.required'     => 'Nomor Dokumen USU harus diisi',
            'partner_doc_no.required' => 'Nomor Dokumen Partner harus diisi',
            'file_name_ori.required'  => 'Dokumen harus diisi',
            'implementation.required' => 'Implementasi harus diisi',
            'unit.required'           => 'Unit harus diisi',
//            'contract_amount.required' => 'Dokumen harus diisi',
        ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator)
        {
            $this->after($validator);
        });
    }


    public function after($validator)
    {
        $check = $this->checkBeforeSave();
        if (count($check) > 0)
        {
            foreach ($check as $item)
            {
                $validator->errors()->add('alert-danger', $item);
            }
        }
    }

    private function checkBeforeSave()
    {
        $ret = [];

        //Check edit
        if (! is_null($this->input('id')))
        {
            $cooperation = Cooperation::find($this->input('id'));
            if (is_null($cooperation))
            {
                $ret[] = 'Kerjasama dengan ID ini tidak ditemukan!';

                return $ret;
            }

            if (! is_null($this->input('coop_type')) && $cooperation->coop_type != $this->input('coop_type'))
            {
                $ret[] = 'Jenis kerjasama tidak boleh diganti, mohon hapus dan buat kerjasama baru jika ingin diganti!';

                return $ret;
            }

            $coop_relations = Cooperation::where('cooperation_id', $cooperation->id)->get();
            if (! $coop_relations->isEmpty())
            {
                $ret[] = 'Kerjasama ini sudah mempunyai MOA / ADDENDUM, perubahan tidak dapat dilakukan lagi!';

                return $ret;
            }
        }

        $coop_partner = Cooperation::where('partner_id', $this->input('partner_id'))->get();

        // dd($coop_partner);
        if (!$coop_partner->isEmpty())
        {
            $ret[] = 'Kerjasama dengan partner ini sudah ada!';

            return $ret;
        }

        if ($this->input('coop_type') == 'MOA')
        {
            $UserAuth = new UserAuth();
            $user = Auth::user();
            if ($UserAuth->isAdminUnit($user))
            {
                
            } elseif ($UserAuth->isAdminProdi($user))
            {

            }

            if($this->input('is_sub_unit') == '1')
            {
                if(is_null($this->input('sub_unit')) ||
                   $this->input('sub_unit') == ''){
                    $ret[] = 'Sub Unit harus dipilih jika kerjasama dilakukan pada sub unit!';
                }
            }

            $job_detail_found = false;
            foreach ($this->input('item_name') as $key => $item)
            {
                if (! is_null($this->input('item_name')[$key]) ||
                    ! is_null($this->input('item_quantity')[$key]) ||
                    ! is_null($this->input('item_uom')[$key]) ||
                    ! is_null($this->input('item_total_amount')[$key]) ||
                    ! is_null($this->input('item_annotation')[$key])
                )
                {
                    if (empty($this->input('item_name')[$key]) ||
                        empty($this->input('item_quantity')[$key]) ||
                        empty($this->input('item_uom')[$key]) ||
                        empty($this->input('item_total_amount')[$key]) ||
                        empty($this->input('item_annotation')[$key])
                    )
                    {
                        $ret[] = 'Mohon melengkapi data detail pekerjaan!';
                    }
                    $job_detail_found = true;
                }
            }
            if (! $job_detail_found)
            {
                $ret[] = 'Mohon mengisi data detail pekerjaan!';
            }
        }

        return $ret;
    }
}
