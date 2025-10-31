<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;

use Illuminate\Support\Facades\Log;

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
        Log::info('=== リクエスト受信 ===');
        Log::info('$request->all():', $request->all());
        Log::info('$request->file():', ['course_image' => $request->file('course_image')]);
        Log::info('Content-Type:', [$request->header('Content-Type')]);
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
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
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
            \Log::error('講座情報削除失敗', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => '削除に失敗しました。',
            ], 500);
        }
    }
}
