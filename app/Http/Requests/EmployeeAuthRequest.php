<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|regex:/^\d{3}-\d{3}-\d{3}$/',
            'password' => 'required', //|size:6',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Wprowadź numer telefonu',
            'phone.regex' => 'Proszę wprowadzić numer telefonu w formacje XXX-XXX-XXX',
            'phone.exists' => 'Wprowadź poprawny numer telefonu',
            'password.required' => 'Wprowadź hasło',
            //'password.size' => 'Nieprawidłowe dane do logowania',
        ];
    }
}
