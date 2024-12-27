<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'immat' => 'required|string|max:255', // Vérifie que le nom est bien une chaîne et qu'il a une longueur maximale
            'email' => "bail|required|email|unique:users,email|max:250",
            'file' => "image",
            'password' => 'required',
            'password_confirm' => 'same:password'
        ];
    }
}