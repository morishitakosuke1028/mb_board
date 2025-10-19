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
            $categories = Category::orderBy('id', 'desc')->get();

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

    public function edit(Category $category): JsonResponse
    {
        return response()->json([
            'data' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = Category::updateCategory($category, $request->only(['name']));

        return response()->json([
            'data' => $updatedCategory,
            'message' => 'カテゴリを更新しました。',
        ]);
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'カテゴリを削除しました。',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('カテゴリ削除失敗', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => '削除に失敗しました。',
            ], 500);
        }
    }
}
