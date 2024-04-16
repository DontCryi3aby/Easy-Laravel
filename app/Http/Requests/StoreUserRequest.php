<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => ['required','string', 'max:50'],
            'middleName' => ['sometimes', 'string','max:50', 'nullable'],
            'lastName' => ['required', 'string','max:50'],
            'mobile' => ['sometimes', 'string', Rule::in(['Iphone', 'Samsung', 'LG', 'Xiaomi']), 'nullable'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email' ,'string','max:50'],
            'passwordHash' => ['required', 'string','min:6', 'max:50'],
            'intro' => ['sometimes', 'string', 'max:255','nullable'],
            'profile' => ['sometimes', 'string', 'max:255', 'nullable'],
        ];
    }
}