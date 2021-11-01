<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeRegisterRequest extends FormRequest
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
            'first_name' => 'required|min:3|max:30',
            'last_name' => 'required|min:3|max:30',
            'phone' => 'required|regex:/^\d{3}-\d{3}-\d{3}$/',
            'registration_code' => 'required|size:8',
            'agreement_1' => 'required|accepted',
            //'agreement_2' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'registration_code.required' => 'Proszę wprowadzić kod rejestracyjny',
            'registration_code.size' => 'Proszę wprowadzić poprawny kod rejestracyjny',
            'first_name.required' => 'Proszę wprowadzić imię',
            'first_name.min' => 'Proszę wprowadzić poprawne imię (min: :min znaków)',
            'first_name.max' => 'Proszę wprowadzić poprawne imię (max: :max znaków)',
            'last_name.required' => 'Proszę wprowadzić nazwisko',
            'last_name.min' => 'Proszę wprowadzić poprawne nazwisko (min: :min znaków)',
            'last_name.max' => 'Proszę wprowadzić poprawne nazwisko (max: :max znaków)',
            'phone.required' => 'Proszę wprowadzić numer telefonu',
            'phone.regex' => 'Proszę wprowadzić numer telefonu w formacje XXX-XXX-XXX',
            'agreement_1.required' => 'Akceptacja regulaminu jest obowiązkowa',
            'agreement_1.accepted' => 'Akceptacja regulaminu jest obowiązkowa',
            'agreement_2' => 'Zgoda jest obowiązkowa',
        ];
    }
}
