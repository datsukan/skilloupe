<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillRequest extends FormRequest
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
            'id'                                => ['required', 'integer', 'exists:users,id'],
            'skill_list'                        => ['array'],
            'skill_list.*.id'                   => ['nullable', 'integer', 'exists:skills,id'],
            'skill_list.*.name'                 => ['required', 'string', 'max:50'],
            'skill_list.*.type'                 => ['required', 'string', 'max:50'],
            'skill_list.*.level'                => ['required', 'numeric', 'between:0,5'],
            'skill_list.*.experience_period'    => ['required', 'numeric', 'between:0,100'],
            'skill_list.*.tool'                 => ['nullable', 'array', 'max:100'],
            'skill_list.*.tool.*'               => ['string'],
            'skill_list.*.platform'             => ['nullable', 'array', 'max:100'],
            'skill_list.*.platform.*'           => ['string'],
            'skill_list.*.system'               => ['nullable', 'array', 'max:100'],
            'skill_list.*.system.*'             => ['string'],
            'skill_list.*.experience_detail'    => ['nullable', 'string', 'max:1000'],
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
