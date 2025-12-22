<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $owner = $request->user();

        $courses = Course::where('owner_id', $owner->id)
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $courses]);
    }

    public function store(StoreCourseRequest $request)
    {
        $owner = $request->user();
        $data = $request->validated();
        if (!empty($data['date_time'])) {
            $data['date_time'] = str_replace('T', ' ', $data['date_time']);
        }
        $data['owner_id'] = $owner->id;

        $course = Course::createWithImage($data, $request->file('course_image'));

        return response()->json([
            'message' => '講座を登録しました。',
            'data' => $course,
        ], 201);
    }

    public function edit(Request $request, Course $course)
    {
        $this->authorizeOwner($request, $course);

        return response()->json(['data' => $course]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorizeOwner($request, $course);

        $data = $request->validated();
        if (!empty($data['date_time'])) {
            $data['date_time'] = str_replace('T', ' ', $data['date_time']);
        }

        $course->updateWithImage($data, $request->file('course_image'));

        return response()->json([
            'message' => '講座を更新しました。',
            'data' => $course->fresh(),
        ]);
    }

    public function destroy(Request $request, Course $course)
    {
        $this->authorizeOwner($request, $course);

        $course->delete();

        return response()->json([
            'message' => '講座を削除しました。',
        ]);
    }

    private function authorizeOwner(Request $request, Course $course): void
    {
        if ($course->owner_id !== $request->user()->id) {
            abort(403, '権限がありません。');
        }
    }
}
