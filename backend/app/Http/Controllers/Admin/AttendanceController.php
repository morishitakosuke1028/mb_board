<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(): JsonResponse
    {
        $attendances = Attendance::with(['user', 'course'])
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $attendances,
            'users' => $attendances->pluck('user')->filter()->unique('id')->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            })->values(),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);

        AttendanceHistory::create([
            'attendance_id' => $attendance->id,
            'user_id' => $attendance->user_id,
            'message' => $request->input('message'),
            'deleted_at' => now(),
        ]);

        $attendance->delete();

        return response()->json([
            'message' => '受講情報を削除しました。',
        ]);
    }
}
