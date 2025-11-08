<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Admin\CourseImportService;
use App\Models\Course;
use League\Csv\Reader;
use League\Csv\Statement;
use Exception;

class CourseImportController extends Controller
{
    public function __construct(private CourseImportService $service) {}

    public function downloadSample()
    {
        $path = storage_path('app/public/sample_csv/course_sample.csv');

        if (!file_exists($path)) {
            \Log::error("サンプルCSVが存在しません: {$path}");
            return response()->json(['message' => 'サンプルCSVが存在しません。'], 404);
        }

        return response()->download($path, 'course_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(CourseImportRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('imports', 'local');

        $fullPath = storage_path('app/' . $path);
        $count = $this->service->import($fullPath);

        return response()->json([
            'message' => "{$count}件の講座をインポートしました。",
            'count' => $count,
        ], 200);
    }
}
