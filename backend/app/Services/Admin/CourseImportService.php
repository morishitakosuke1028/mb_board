<?php

namespace App\Services\Admin;

use App\Models\Course;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseImportService
{
    public function import(string $filePath): int
    {
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $records = (new Statement())->process($csv);

            $count = 0;
            foreach ($records as $record) {
                Course::createFromCsvRecord($record);
                $count++;
            }

            return $count;
        } catch (Exception $e) {
            Log::error('CSVインポートエラー: ' . $e->getMessage());
            throw $e;
        }
    }
}
