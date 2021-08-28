<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'id' =>                                 ['required', 'integer', 'exists:users,id'],
            'project_list' =>                       ['array'],
            'project_list.*.id' =>                  ['nullable', 'integer', 'exists:projects,id'],
            'project_list.*.title' =>               ['required', 'string', 'max:50'],
            'project_list.*.started_at' =>          ['required', 'string', 'date_format:Y-m'],
            'project_list.*.ended_at' =>            ['required', 'string', 'date_format:Y-m'],
            'project_list.*.summary' =>             ['required', 'string', 'max:500'],
            'project_list.*.member' =>              ['required', 'integer', 'between:1,999999'],
            'project_list.*.system' =>              ['nullable', 'array', 'max:100'],
            'project_list.*.system.*' =>            ['string'],
            'project_list.*.region' =>              ['nullable', 'array', 'max:100'],
            'project_list.*.region.*' =>            ['string'],
            'project_list.*.role' =>                ['array', 'between:1,100'],
            'project_list.*.role.*' =>              ['string'],
            'project_list.*.dev_method' =>          ['nullable', 'array', 'max:100'],
            'project_list.*.dev_method.*' =>        ['string'],
            'project_list.*.process' =>             ['array', 'between:1,100'],
            'project_list.*.process.*' =>           ['string'],
            'project_list.*.lang_and_fw' =>         ['nullable', 'array', 'max:100'],
            'project_list.*.lang_and_fw.*' =>       ['string'],
            'project_list.*.os_and_mw' =>           ['nullable', 'array', 'max:100'],
            'project_list.*.os_and_mw.*' =>         ['string'],
            'project_list.*.tool' =>                ['nullable', 'array', 'max:100'],
            'project_list.*.tool.*' =>              ['string'],
            'project_list.*.experience_detail' =>   ['required', 'string', 'max:1000'],
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
