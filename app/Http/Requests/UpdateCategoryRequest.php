<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
                "parentId"=> ["sometimes", "exists:categories,id", "nullable"],
                "title"=> ["required", "string", "max:75"],
                "metaTitle"=> ["required", "string", "max:100", "nullable"],
                "slug"=> ["required", "string", "max:100", "nullable"],
                "content"=> ["sometimes", "string", "nullable"],
            ];
        } else {
            return [
                "parentId"=> ["sometimes", "exists:categories,id", "nullable"],
                "title"=> ["sometimes", "string", "max:75"],
                "metaTitle"=> ["sometimes", "string", "max:100", "nullable"],
                "slug"=> ["sometimes", "string", "max:100", "nullable"],
                "content"=> ["sometimes", "string", "nullable"],
            ];
        }
    }
}