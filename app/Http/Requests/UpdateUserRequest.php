<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user) //acepta modelos o ids (podría ser $this->user->id)
            ],
            'password' => ['nullable', 'min:6'],
            'role' => [
                'required',
                Rule::in(Role::getList())
            ],
            'profession_id' => [
                'nullable',
                'present',
                Rule::exists('professions', 'id')->whereNull('deleted_at')
            ],
            'bio' => 'required',
            'twitter' => [
                'url',
                'nullable',
                'present'
            ],
            'skills' => [
                'array',
                Rule::exists('skills', 'id')
            ]
        ];
    }

    public function messages()
    {
        return [
            'profession_id.present' => 'The profession field must be present.',
            'profession_id.exists' => 'The selected profession is not valid.',
            'skills.exists' => 'The chosen skill is not valid.',
            'twitter.url' => 'The twitter field must be an url'
        ];
    }

    public function updateUser(User $user)
    {
        $data = $this->validated();

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data);
        $user->role = $data['role'];
        $user->update();

        $user->profile->update($data);

        $user->skills()->sync($data['skills'] ?? []);
    }
}
