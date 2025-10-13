<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            \Log::info('Admin CategoryController@index called', [
                'token' => request()->bearerToken(),
                'auth_user' => auth('admin')->user(),
            ]);

            $admin = auth('admin')->user();

            if (! $admin) {
                \Log::warning('Unauthorized access attempt to categories');
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $categories = \App\Models\Category::orderBy('id', 'desc')->get();

            \Log::info('Categories fetched successfully', [
                'count' => count($categories),
                'admin' => $admin->email ?? 'N/A',
            ]);

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

    public function store(StoreCategoryRequest $request)
    {

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
