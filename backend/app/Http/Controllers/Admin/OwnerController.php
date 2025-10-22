<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OwnerController extends Controller
{
    public function index(): JsonResponse
    {
        $owners = Owner::orderByDesc('id')->get();

        return response()->json([
            'data' => $owners,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $owner = Owner::findOrFail($id);
        $owner->delete();

        return response()->json([
            'message' => 'オーナーを削除しました。',
        ]);
    }
}
