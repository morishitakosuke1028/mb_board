<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Course;

class AttendanceController extends Controller
{
    /**
     * ログイン中ユーザーの参加している講座一覧
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // user_id が一致する course 一覧を取得
        $attendances = Attendance::with('course')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($attendances);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'status'    => 'nullable|string',
        ]);

        $userId = $request->user()->id;
        $courseId = $request->course_id;

        // すでに参加していないか確認（ユニーク制約）
        $exists = Attendance::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'すでに参加済みです',
            ], 409); // Conflict
        }

        $attendance = Attendance::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => $request->status ?? '参加',
        ]);

        return response()->json([
            'message' => '参加が完了しました',
            'data' => $attendance,
        ]);
    }

    /**
     * ★ 参加済みチェック
     */
    public function check(Request $request, $courseId)
    {
        $userId = $request->user()->id;

        $attending = Attendance::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        return response()->json([
            'attending' => $attending,
        ]);
    }

    /**
     * ★（既存）キャンセル
     */
    public function destroy(Request $request, Attendance $attendance)
    {
        if ($attendance->user_id !== $request->user()->id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $attendance->delete();

        return response()->json(['message' => 'キャンセルしました']);
    }
}
