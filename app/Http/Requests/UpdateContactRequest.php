<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('contacts', 'email')->ignore($this->contact)
            ],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-\(\)]{6,20}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name may not be greater than :max characters.',

            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.regex' => 'The phone format is invalid. Use (xx) xxxxx-xxxx.',
        ];
    }
}
