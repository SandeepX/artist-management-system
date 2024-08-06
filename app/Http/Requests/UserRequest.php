<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dob' => ['nullable', 'date', 'before:today'],
            'role' => ['required', Rule::in(['super_admin', 'artist', 'artist_manager'])],
            'gender' => ['required', Rule::in(['m', 'f', 'o'])],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9\s\-\(\)]+$/'],

        ];

        if ($this->isMethod('put')) {
            $userId = $this->route('id');
            $rules['email'] = Rule::unique('users', 'email')->ignore($userId);
        } else {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['email'] = Rule::unique('users', 'email');
        }
        return $rules;
    }
}
