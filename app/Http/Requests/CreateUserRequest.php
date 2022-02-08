<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User as User;
use Illuminate\Support\Facades\DB as DB;
use App\Profession as Profession;
use Illuminate\Validation\Rule as Rule;

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
            'profession_id' => [
                'nullable',
                Rule::exists('professions', 'id')->whereNull('deleted_at')
            ],
            'password' => ['required', 'min:6'],
            'twitter' => ['url', 'nullable'],
            'bio' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'profession_id.exists' => 'The chosen profession is not valid.'
        ];
    }

    public function createUser()
    {
        DB::transaction(function () {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password)
            ]);

            $user->profile()->create([
                'profession_id' => $this->profession_id,
                'twitter' => $this->twitter,
                'bio' => $this->bio
            ]);
        });
    }
}
