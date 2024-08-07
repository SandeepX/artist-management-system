<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ArtistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'super_admin' || Auth::user()->role == 'artist_manager';
    }
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dob' => ['nullable', 'date', 'before:today'],
            'gender' => ['required', Rule::in(['m', 'f', 'o'])],
            'address' => ['nullable', 'string', 'max:100'],
            'first_release_year' => ['nullable','date']
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
