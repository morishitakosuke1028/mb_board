<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    public function index(): JsonResponse
    {
        $attendances = Attendance::with(['user', 'course'])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $attendances,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => '受講情報を削除しました。',
        ]);
    }
}
