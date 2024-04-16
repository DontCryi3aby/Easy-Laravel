<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $method = $this->method();
        if($method == "PUT") {
            return [
                'firstName' => ['required','string', 'max:50'],
                'middleName' => ['sometimes', 'string','max:50', 'nullable'],
                'lastName' => ['required', 'string','max:50'],
                'mobile' => ['required', 'string', Rule::in(['Iphone', 'Samsung', 'LG', 'Xiaomi']), 'nullable'],
                'email' => ['required', 'email:rfc,dns',
                    function ($attribute, $value, $fail) {
                        if ($value !== User::find($this->route('user'))->$attribute) {
                            $fail("You are not allowed to update the email.");
                        }
                    }, 'max:50'],
                'passwordHash' => ['required', 'string','min:6', 'max:50'],
                'intro' => ['required', 'string', 'max:255','nullable'],
                'profile' => ['required', 'string', 'max:255', 'nullable'],
            ];
        } else {
            return [
                'firstName' => ['sometimes','string', 'max:50'],
                'middleName' => ['sometimes', 'string','max:50', 'nullable'],
                'lastName' => ['sometimes', 'string','max:50'],
                'mobile' => ['sometimes', 'string', Rule::in(['Iphone', 'Samsung', 'LG', 'Xiaomi']), 'nullable'],
                'email' => ['sometimes', 'email:rfc,dns',
                function ($attribute, $value, $fail) {
                    if ($value !== User::find($this->route('user'))->$attribute) {
                        $fail("You are not allowed to update the email.");
                    }
                }, 'max:50'],
                'passwordHash' => ['sometimes', 'string','min:6', 'max:50'],
                'intro' => ['sometimes', 'string', 'max:255','nullable'],
                'profile' => ['sometimes', 'string', 'max:255', 'nullable'],
            ];
        }
    }
}