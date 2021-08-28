<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowSkillListRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:users,id']
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
