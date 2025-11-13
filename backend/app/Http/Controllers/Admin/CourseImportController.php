<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Admin\CourseImportService;
use App\Http\Requests\Admin\CourseImportRequest;
use App\Models\Course;
use League\Csv\Reader;
use League\Csv\Statement;
use Exception;

use Illuminate\Support\Facades\Log;

class CourseImportController extends Controller
{
    public function __construct(private CourseImportService $service) {}

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
        } catch (\Throwable $e) {
            Log::error('❌ CSVインポート失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'CSVインポート中にエラーが発生しました。',
                'error' => $e->getMessage(),
            ], 500);
        }

        Log::info('CSV保存先', [
            'path' => $path,
            'fullPath' => $fullPath,
            'exists' => file_exists($fullPath),
        ]);

        return response()->json([
            'message' => "{$count} 件の講座をインポートしました。",
            'count' => $count,
        ], 200);
    }
}
