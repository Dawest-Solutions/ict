<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeRegisterCodeRequest extends FormRequest
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
            'registration_code' => 'required|size:8|exists:employees,registration_code',
        ];
    }

    public function messages(): array
    {
        return [
            'registration_code.required' => 'Proszę wprowadzić kod rejestracyjny',
            'registration_code.size' => 'Proszę wprowadzić poprawny kod rejestracyjny',
            'registration_code.exists' => 'Proszę wprowadzić poprawny kod rejestracyjny',
        ];
    }
}
