<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * 最新の講座を5件返す
     */
    public function latest()
    {
        $courses = Course::orderBy('date_time', 'desc')
            ->where('status', 1) // 公開ステータスのみ取得する場合
            ->limit(5)
            ->get();

        return response()->json($courses);
    }

    /**
     * 講座一覧
     */
    public function index()
    {
        $courses = Course::orderBy('date_time', 'desc')
            ->where('status', 1)
            ->paginate(20);

        return response()->json($courses);
    }

    /**
     * 講座詳細
     */
    public function show($id)
    {
        $course = Course::where('status', 1)->findOrFail($id);

        return response()->json($course);
    }
}
