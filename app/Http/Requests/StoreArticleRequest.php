<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'article_number' => 'required|string|max:100|unique:articles,article_number',
            'part_number' => 'required|string|max:100|unique:articles,part_number',
            'part_name' => 'nullable|string|max:200',
            'details' => 'required|array|min:1',
            'details.*.dept_code' => 'required|string|in:sales,quality,ppc,design engineering',
            'details.*.requirement' => 'required|string|max:255',
            'details.*.check_by' => 'nullable|exists:users,id',
            'details.*.date' => 'nullable|date',
            'details.*.remark' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'details.required' => 'At least one detail is required.',
            'details.*.dept_code.required' => 'Department code is required for each detail.',
            'details.*.requirement.required' => 'Requirement is required for each detail.',
        ];
    }
}
