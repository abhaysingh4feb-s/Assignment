<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'The contact name is required.',
            'phone.required' => 'The phone number is required.',
            'email.email' => 'Please enter a valid email address.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
        ];
    }
}
