<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as Rule;
use App\User as User;

class UpdateUserRequest extends FormRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  *
    //  * @return bool
    //  */
    // public function authorize()
    // {
    //     return true;
    // }

    // /**
    //  * Get the validation rules that apply to the request.
    //  *
    //  * @return array
    //  */
    // public function rules()
    // {
    //     return [
    //         'name' => 'required',
    //         'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
    //         'profession' => 'exists:professions,title',
    //         'password' => ''
    //     ];
    // }

    // // public function messages()
    // // {
    // //     return [];
    // // }

    // // $data = $this->validated();

    // public function UpdateUser()
    // {
    //     $this->password = bcrypt($this->password) ?? null;

    //     $user->update($this->validated());
    // }
}
