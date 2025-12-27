<?php

namespace App\Services;

use App\Models\Course;
use App\Validators\CourseCsvValidator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseImportService
{
    public function import(string $filePath): int
    {
        try {
            $csv = Reader::createFromFileObject(new \SplFileObject($filePath));
            $csv->setHeaderOffset(0);
            $records = (new Statement())->process($csv);

            $count = 0;
            $line = 2; // 1行目はヘッダーなので、データは2行目から開始

            $errors = [];

            foreach ($records as $record) {
                $record = (array)$record;
                $validator = CourseCsvValidator::validate($record, $line);

                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $msg) {
                        $errors[] = "{$msg}";
                    }
                } else {
                    Course::createFromCsvRecord($record);
                }

                $line++;
                $count++;
            }

            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            return $count;

        } catch (Exception $e) {
            Log::error('CSVインポートエラー: ' . $e->getMessage());
            throw $e;
        }
    }
}
