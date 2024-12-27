<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'immat' => "bail|sometimes|required|unique:users|max:250",
            'email' => "bail|sometimes|required|email|unique:users,email|max:250",
            'file' => "image",
            'password' => 'sometimes|required',
            'password_confirm' => 'sometimes|required|same:password' 
        ];
    }
}