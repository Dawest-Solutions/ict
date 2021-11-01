<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RewardUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:App\Models\Reward,id',
            'name' => 'required',
            'image' => 'required|image',
            'location' => 'required',
            'service' => 'required',
            'description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Id is required',
            'id.exists' => 'Reward with given id does not exist',
            'name' => 'Name is required',
            'image' => 'Image is required',
            'image.image' => 'Image must be an image file (jpg, jpeg, png, bmp, gif, svg, or webp)',
            'location' => 'Location is required',
            'value' => 'Service is required',
            'description' => 'Description is required',
        ];
    }

    /**
     * @return array
     */
    public function filter(): array
    {
        return array_filter($this->all());
    }
}
