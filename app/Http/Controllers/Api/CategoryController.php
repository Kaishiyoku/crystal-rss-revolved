<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'categories' => Auth::user()->categories()->withCount('feeds')->get(),
            'canCreate' => Auth::user()->can('create', Category::class),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Auth::user()->categories()->save(new Category($validated));

        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category,
            'canDelete' => Auth::user()->can('delete', $category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $validated = $request->validated();

        $category->update($validated);

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json();
    }
}
