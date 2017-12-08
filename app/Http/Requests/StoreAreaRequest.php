<?php

namespace App\Http\Requests;

use App\AreasCoop;
use App\Partner;
use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest {
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
        return [
            'area_coop'    => 'required|max:191|string',
        ];
    }

    public function messages()
    {
        return [
            'area_coop.required'    => 'Bidang Kerjasama harus diisi!',
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

        if (! is_null($this->input('id')))
        {
            if (! AreasCoop::where('id', $this->input('id'))->exists())
            {
                $ret[] = 'Bidang Kerjasama tidak ditemukan';
            }
        }else{
            $check = AreasCoop::where('area_coop',$this->input('area_coop'))->exists();
            if($check){
                $ret[] = 'Bidang Kerjasama telah terdaftar, silahkan isi nama bidang kerjasama lain';
            }
        }

        return $ret;
    }
}
