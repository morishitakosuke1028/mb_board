<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\CourseImportService;
use App\Http\Requests\CourseImportRequest;
use App\Models\Course;
use League\Csv\Reader;
use League\Csv\Statement;
use Exception;

class CourseImportController extends Controller
{
    public function downloadSample()
    {
        $path = storage_path('app/public/sample_csv/course_sample.csv');

        if (!file_exists($path)) {
            return response()->json(['message' => 'サンプルCSVが存在しません。'], 404);
        }

        return response()->download($path, 'course_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(CourseImportRequest $request)
    {
        if (!$request->hasFile('csv_file')) {
            return response()->json(['message' => 'CSVファイルを選択してください。'], 422);
        }

        $file = $request->file('csv_file');
        $path = $file->store('imports', 'private');
        $fullPath = storage_path('app/private/' . $path);

        try {
            $count = $this->service->import($fullPath);

            return response()->json([
                'message' => "{$count} 件の講座をインポートしました。",
                'count' => $count,
            ], 200);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),  // ← フロント表示用
            ], 422);
        }
    }
}
