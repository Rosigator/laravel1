<?php

namespace App\Http\Requests;

use App\Role;
use App\User as User;
use Illuminate\Validation\Rule as Rule;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => [
                'nullable',
                Rule::in(Role::getList())
            ],
            'profession_id' => [
                'nullable',
                'present',
                Rule::exists('professions', 'id')->whereNull('deleted_at')
            ],
            'skills' => [
                'array',
                Rule::exists('skills', 'id')
            ],
            'password' => ['required', 'min:6'],
            'twitter' => ['url', 'nullable', 'present'],
            'bio' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'profession_id.exists' => 'The chosen profession is not valid.',
            'skills.exists' => 'The chosen skill is not valid.',
        ];
    }

    public function createUser()
    {
        DB::transaction(function () {

            $user = new User([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password)
            ]);

            $user->role = $this->role ?? 'user';

            $user->save();

            $user->profile()->create([
                'profession_id' => $this->profession_id,
                'twitter' => $this->twitter,
                'bio' => $this->bio
            ]);

            $user->skills()->attach($this->skills ?? []);
        });
    }
}
