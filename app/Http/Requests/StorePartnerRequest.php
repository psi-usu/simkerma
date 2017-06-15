<?php

namespace App\Http\Requests;

use App\Partner;
use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequest extends FormRequest {
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
            'name'    => 'required|max:191|string',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Nama Instansi Partner harus diisi!',
            'name.max'         => 'Maksimal Nama Instansi Partner adalah 191 karakter!',
            'address.required' => 'Alamat Instansi Partner harus diisi!',
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
            if (! Partner::where('id', $this->input('id'))->exists())
            {
                $ret[] = 'Instansi Partner tidak ditemukan';
            }
        }

        return $ret;
    }
}
