<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomSearchRequest extends FormRequest
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
            'profile'                               => ['required', 'array'],
            'profile.name'                          => ['nullable', 'string'],
            'profile.age'                           => ['nullable', 'integer'],
            'profile.age_operator'                  => ['nullable', 'required_with:profile.age', 'string'],
            'profile.experience_period'             => ['nullable', 'numeric'],
            'profile.experience_period_operator'    => ['nullable', 'required_with:profile.age_operator', 'string'],
            'profile.sex'                           => ['nullable', 'integer', 'between:1,3'],
            'skill'                                 => ['required', 'array'],
            'skill.name'                            => ['nullable', 'string'],
            'skill.type'                            => ['nullable', 'string'],
            'skill.level'                           => ['nullable', 'numeric'],
            'skill.level_operator'                  => ['nullable', 'required_with:skill.level', 'string'],
            'skill.experience_period'               => ['nullable', 'numeric'],
            'skill.experience_period_operator'      => ['nullable', 'required_with:skill.experience_period', 'string'],
            'skill.tool'                            => ['nullable', 'string'],
            'skill.platform'                        => ['nullable', 'string'],
            'skill.system'                          => ['nullable', 'string'],
            'project'                               => ['required', 'array'],
            'project.title'                         => ['nullable', 'string'],
            'project.started_at'                    => ['nullable', 'string', 'date_format:Y-m'],
            'project.started_at_operator'           => ['nullable', 'required_with:project.started_at', 'string'],
            'project.ended_at'                      => ['nullable', 'string', 'date_format:Y-m'],
            'project.ended_at_operator'             => ['nullable', 'required_with:project.ended_at', 'string'],
            'project.member'                        => ['nullable', 'integer'],
            'project.member_operator'               => ['nullable', 'required_with:project.member', 'string'],
            'project.system'                        => ['nullable', 'string'],
            'project.region'                        => ['nullable', 'string'],
            'project.role'                          => ['nullable', 'string'],
            'project.dev_method'                    => ['nullable', 'string'],
            'project.process'                       => ['nullable', 'string'],
            'project.lang_and_fw'                   => ['nullable', 'string'],
            'project.os_and_mw'                     => ['nullable', 'string'],
            'project.tool'                          => ['nullable', 'string'],
            'qualification'                         => ['required', 'array'],
            'qualification.name'                    => ['nullable', 'string'],
        ];
    }
}
