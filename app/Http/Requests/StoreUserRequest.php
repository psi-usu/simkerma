<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest {
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
            'username'  => 'required|unique:users,username|max:100',
            'password'  => 'required',
            'full_name' => 'required|max:191',
            'email'     => 'max:191',
            'unit'      => 'required|max:30',
        ];
    }

    public function messages()
    {
        return [
            'username.required'  => 'Username harus diisi!',
            'username.unique'    => 'Username sudah pernah digunakan!',
            'password.required'  => 'Password harus diisi!',
            'full_name.required' => 'Nama harus diisi!',
            'unit.required'      => 'Unit harus diisi!',
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

        if (! empty($this->input('unit')))
        {
            $user = Auth::user();
            if(isset($user->unit))
            {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'http://api.usu.ac.id/1.0/faculties/' . $user->unit . '/study_programs');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $study_programs = json_decode(curl_exec($curl));
                curl_close($curl);

                $unit_allowed = false;
                foreach ($study_programs as $study_program)
                {
                    if($this->input('unit') == $study_program->name)
                    {
                        $unit_allowed = true;
                        break;
                    }
                }
                if(!$unit_allowed)
                {
                    $ret[] = 'Anda tidak diperbolehkan untuk membuat user pada unit ini!';
                }
            }
        }

        return $ret;
    }
}
