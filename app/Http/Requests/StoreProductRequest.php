<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title' => ['required','string', 'max:255'],
            'body_html' => ['sometimes', 'string', 'nullable'],
            'vendor' => ['required', 'string','max:255'],
            'product_type' => ['required', 'string'],
            'handle' => ['required','string','max:255'],
            'published_at' => ['sometimes', 'date','nullable'],
            'template_suffix' => ['sometimes', 'string', 'max:255','nullable'],
            'published_scope' => ['sometimes', 'string', 'max:255', 'nullable'],
            'status' => ['required', 'string', 'max:255'],
            'admin_graphql_api_id' => ['sometimes', 'string', 'max:255', 'nullable'],
        ];
    }
}