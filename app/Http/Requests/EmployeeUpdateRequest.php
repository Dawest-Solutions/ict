<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * @return array
     */
    public function filter(): array
    {

        $this->merge([
            'agreement_1' => $this->boolean('agreement_1'),
            'agreement_2' => $this->boolean('agreement_2'),
            'active' => $this->boolean('active'),
        ]);

        return $this->all();
    }
}
