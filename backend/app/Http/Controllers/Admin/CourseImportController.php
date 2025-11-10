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
        Log::info('RAW dump', [
            'contentType' => $request->header('Content-Type'),
            'hasFile' => $request->hasFile('csv_file'),
            'allKeys' => array_keys($request->all()),
            'files' => array_keys($request->allFiles()),
        ]);

        if (!$request->hasFile('csv_file')) {
            return response()->json(['message' => 'CSVファイルを選択してください。'], 422);
        }

        $file = $request->file('csv_file');
        $path = $file->store('imports', 'local');

        return response()->json(['message' => "CSV受信成功: {$path}"]);
    }
}
