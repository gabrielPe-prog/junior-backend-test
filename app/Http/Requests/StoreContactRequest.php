<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => ['required', 'string', 'regex:/^(\(\d{2}\)\s?\d{4,5}-\d{4}|\d{10,11})$/'],
        ];
    }

    public function messages()
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
