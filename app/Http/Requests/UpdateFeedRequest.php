<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Rules\ValidFeedUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam category_id int required The ID of the category.
 * @bodyParam feed_url string required The URL of the feed.
 * @bodyParam site_url string required The URL of the website.
 * @bodyParam name string required THe name of the feed.
 */
class UpdateFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->feed);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => [
                'required',
                Rule::in(optional(Category::getAvailableOptions())->keys()),
            ],
            'feed_url' => [
                'required',
                'url',
                new ValidFeedUrl(),
            ],
            'site_url' => [
                'required',
                'url',
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('feeds', 'name')->where('user_id', optional($this->user())->id)->ignore($this->feed),
            ],
        ];
    }

    public function bodyParameters()
    {
        return [
            'category_id',
            'feed_url',
            'site_url',
            'name',
        ];
    }
}
