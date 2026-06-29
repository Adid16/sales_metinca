<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $articleId = $this->route('article')->id;

        return [
            'article_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('articles', 'article_number')->ignore($articleId)
            ],
            'part_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('articles', 'part_number')->ignore($articleId)
            ],
            'part_name' => 'nullable|string|max:200',
            'details' => 'required|array|min:1',
            'details.*.dept_code' => 'required|string|in:sales,quality,ppc,design engineering',
            'details.*.requirement' => 'required|string|max:255',
            'details.*.check_by' => 'nullable|exists:users,id',
            'details.*.date' => 'nullable|date',
            'details.*.remark' => 'nullable|string|max:500',
            'deleted_details' => 'nullable|array',
            'deleted_details.*' => 'exists:detail_articles,id',
        ];
    }
}
