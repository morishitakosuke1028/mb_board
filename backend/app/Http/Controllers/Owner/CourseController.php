<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $owner = $request->user();
        $validated = $request->validate([
            'course_title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'date_time' => ['required', 'date'],
            'participation_fee' => ['nullable', 'string', 'max:255'],
            'additional_fee' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'venue_zip' => ['nullable', 'string', 'max:20'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['owner_id'] = $owner->id;

        $course = Course::create($validated);

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

    public function update(Request $request, Course $course)
    {
        $this->authorizeOwner($request, $course);

        $validated = $request->validate([
            'course_title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'date_time' => ['required', 'date'],
            'participation_fee' => ['nullable', 'string', 'max:255'],
            'additional_fee' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'venue_zip' => ['nullable', 'string', 'max:20'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $course->update($validated);

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
