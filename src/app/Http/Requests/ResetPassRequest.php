<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassRequest extends FormRequest
{
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
            'id' =>         ['required', 'integer', 'exists:users,id'],
            'password' =>   ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }
}
