<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Rules\ValidFeedUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['required', Rule::in(Category::getAvailableOptions()->keys())],
            'feed_url' => ['required', 'url', new ValidFeedUrl()],
            'site_url' => ['required', 'url'],
            'name' => ['required', Rule::unique('feeds', 'name')->where('user_id', Auth::user()->id)],
        ];
    }
}
