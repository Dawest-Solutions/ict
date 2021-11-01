<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeResetPasswordRequest extends FormRequest
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
            'phone' => 'required|regex:/^\d{3}-\d{3}-\d{3}$/|exists:employees,phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Proszę wprowadzić numer telefonu',
            'phone.regex' => 'Proszę wprowadzić numer telefonu w formacje XXX-XXX-XXX',
            'phone.exists' => 'Proszę wprowadzić poprawny numer telefonu',
        ];
    }
}
