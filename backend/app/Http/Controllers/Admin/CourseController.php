<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('owner')->orderBy('id', 'desc')->get();

        return response()->json([
            'data' => $courses
        ]);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['date_time'])) {
            $data['date_time'] = str_replace('T', ' ', $data['date_time']);
        }
        $course = Course::createWithImage($data, $request->file('course_image'));

        return response()->json([
            'message' => '講座を登録しました。',
            'data' => $course,
        ], 201);
    }

    public function edit(Course $course)
    {
        if ($course->course_image) {
            $course->course_image = asset($course->course_image);
        }
        return response()->json([
            'data' => $course
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            $data = $request->validated();
            if (!empty($data['date_time'])) {
                $data['date_time'] = str_replace('T', ' ', $data['date_time']);
            }
            if (isset($data['owner_id'])) {
                $data['owner_id'] = (int) $data['owner_id'];
            }
            if (isset($data['status'])) {
                $data['status'] = (int) $data['status'];
            }

            $course->updateWithImage($data, $request->file('course_image'));

            return response()->json([
                'message' => '講座情報を更新しました。',
                'data' => $course->fresh(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => '更新処理中にエラーが発生しました。',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json([
                'message' => '講座情報を削除しました。',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => '削除に失敗しました。',
            ], 500);
        }
    }
}
