<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseImportRequest;
use App\Services\CourseImportService;
use Illuminate\Support\Facades\Storage;

class CourseImportController extends Controller
{
    public function __construct(private CourseImportService $service) {}

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
