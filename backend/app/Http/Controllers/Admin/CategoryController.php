<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $admin = auth('admin')->user();
            $categories = \App\Models\Category::orderBy('id', 'desc')->get();

            return response()->json([
                'data' => $categories,
                'admin' => $admin->only(['id', 'name', 'email']),
            ]);
        } catch (\Throwable $e) {
            \Log::error('CategoryController@index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::createCategory(
            $request->only(['name'])
        );

        return response()->json([
            'data' => $category,
            'message' => 'カテゴリを登録しました。',
        ], 201);
    }

    public function edit(category $category)
    {

    }

    public function update(UpdateCategoryRequest $request)
    {

    }

    public function destroy($id)
    {

    }
}
