<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'authorId' => ['required', "exists:users,id"],
            'title' => ['required', "string" , "max:75"],
            'metaTitle' => ['sometimes', "string" , "max:100", "nullable"],
            'slug' => ['sometimes', "string" , "max:100", "nullable"],
            'sumary' => ['sometimes', "string" , "max:255", "nullable"],
            'published' => ['required', Rule::in([0, 1])],
            'content' => ['sometimes', 'string', 'nullable'],
        ];
    }
}