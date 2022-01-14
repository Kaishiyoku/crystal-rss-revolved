<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

/**
 * @group Categories
 *
 * API methods for managing categories
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response scenario=success [{
     *  "id": 1,
     *  "user_id": 1,
     *  "name": "Technology",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feeds_count": 8
     * },
     * {
     *  "id": 2,
     *  "user_id": 1,
     *  "name": "Poetry",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feeds_count": 3
     * }]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Auth::user()->categories()->withCount('feeds')->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @response scenario=success {
     *  "id": 3,
     *  "user_id": 1,
     *  "name": "Blogs",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feeds_count": 0
     * }
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category($request->validated());

        Auth::user()->categories()->save($category);

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @response scenario=success {
     *  "id": 1,
     *  "user_id": 1,
     *  "name": "Technology",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feeds_count": 8
     * }
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @response scenario=success [{
     *  "id": 1,
     *  "user_id": 1,
     *  "name": "Tech & IT",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-23T12:26:11.000000Z",
     *  "feeds_count": 8
     * }
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @response scenario=success {}
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->feeds()->delete();

        $category->delete();

        return response()->json();
    }
}
