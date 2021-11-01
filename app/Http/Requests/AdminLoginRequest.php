<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:admins,email',
            'password' => 'required|min:3|max:50',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Wprowadź adres email',
            'email.exists' => 'Wprowadź poprawny adres email',
            'password.required' => 'Wprowadź hasło',
            'password.min' => 'Wprowadź poprawne hasło',
            'password.max' => 'Wprowadź poprawne hasło',
        ];
    }
}
