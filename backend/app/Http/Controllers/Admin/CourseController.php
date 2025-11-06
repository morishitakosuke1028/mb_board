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

    // public function update(UpdateCourseRequest $request, Course $course)
    // {
    //     $data = $request->validated();

    //     if (!empty($data['date_time'])) {
    //         $data['date_time'] = str_replace('T', ' ', $data['date_time']);
    //     }

    //     $course->updateWithImage($data, $request->file('course_image'));

    //     return response()->json([
    //         'message' => '講座情報を更新しました。',
    //         'data' => $course->fresh(),
    //     ]);
    // }
    public function update(UpdateCourseRequest $request, Course $course)
    {
        \Log::info('=== [CourseController@update] リクエスト受信 ===');
        \Log::info('Request all:', $request->all());
        \Log::info('Request file:', ['course_image' => $request->file('course_image')]);
        \Log::info('Content-Type:', [$request->header('Content-Type')]);

        try {
            // バリデーション済みデータ
            $data = $request->validated();
            \Log::info('Validated data:', $data);

            // 日付整形
            if (!empty($data['date_time'])) {
                $data['date_time'] = str_replace('T', ' ', $data['date_time']);
                \Log::info('日付整形後:', ['date_time' => $data['date_time']]);
            }

            // 数値にキャスト
            if (isset($data['owner_id'])) {
                $data['owner_id'] = (int) $data['owner_id'];
            }
            if (isset($data['status'])) {
                $data['status'] = (int) $data['status'];
            }

            // 画像の有無をログ
            if ($request->hasFile('course_image')) {
                \Log::info('画像アップロードあり:', [
                    'name' => $request->file('course_image')->getClientOriginalName(),
                    'size' => $request->file('course_image')->getSize(),
                    'mime' => $request->file('course_image')->getMimeType(),
                ]);
            } else {
                \Log::info('画像アップロードなし（既存画像維持）');
            }

            // モデルの更新
            $course->updateWithImage($data, $request->file('course_image'));
            \Log::info('updateWithImage 実行完了:', ['id' => $course->id]);

            return response()->json([
                'message' => '講座情報を更新しました。',
                'data' => $course->fresh(),
            ]);
        } catch (\Throwable $e) {
            // 例外キャッチして詳細出力
            \Log::error('updateメソッド内で例外発生', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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
