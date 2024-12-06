<?php

namespace App\Http\Requests;

use App\Http\Dto\AuthorListDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorListRequest extends FormRequest
{
    public const COLUMNS = [
        'full_name',
        'gender',
        'count_posts',
        'count_posts_last_month',
        'average_rating_published_posts',
        'average_rating_published_posts_last_month',
        'title_last_published_post',
        'content_last_published_post',
    ];

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
            'columns' => [
                'required',
                'array',
                Rule::in(self::COLUMNS)
            ],
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:50',
            'sort_by' => [
                'nullable',
                Rule::in(self::COLUMNS),
            ],
            'filter' => 'nullable|array',
        ];
    }

    public function getDto(): AuthorListDto
    {
        return new AuthorListDto(
            $this->get('columns'),
            $this->get('offset'),
            $this->get('limit'),
            $this->get('sort_by'),
            $this->get('sort_order'),
            $this->get('filter'),
            $this->get('need_total'),
        );
    }
}
