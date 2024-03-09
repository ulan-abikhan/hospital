<?php

namespace App\Http\Requests;

use App\Utils\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'required|max:255|alpha',
            'last_name'=>'required|max:255|alpha',
            'middle_name'=>'max:255|alpha',
            'email'=>'required|email:rfs,dns|unique:users,email',
            'password'=>'required|min:8|max:100|string',
            'photo'=>'image',
            'role'=>[Rule::enum(UserRoles::class)]
        ];
    }
}
