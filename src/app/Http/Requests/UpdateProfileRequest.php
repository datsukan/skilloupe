<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'id'                => ['required', 'integer', 'exists:users,id'],
            'icon'              => ['nullable', 'sometimes', 'image'],
            'sex'               => ['required', 'integer', 'between:1,3'],
            'age'               => ['required', 'integer', 'between:1,100'],
            'experience_period' => ['required', 'integer', 'between:0,100'],
            'self_introduction' => ['nullable', 'string', 'between:0,500'],
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        if (isset($this->skill)) {
            $data['id'] = $this->skill;
        }

        return $data;
    }
}
