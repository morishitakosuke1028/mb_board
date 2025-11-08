<?php

namespace App\Services\Admin;

use App\Models\Course;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseImportService
{
    /**
     * CSVを読み込み講座をDBに登録する
     */
    public function import(string $filePath): int
    {
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $records = (new Statement())->process($csv);

            $count = 0;
            foreach ($records as $record) {
                Course::create([
                    'owner_id' => $record['owner_id'] ?? null,
                    'course_title' => $record['course_title'] ?? '',
                    'content' => $record['content'] ?? '',
                    'instructor' => $record['instructor'] ?? '',
                    'instructor_title' => $record['instructor_title'] ?? '',
                    'date_time' => $record['date_time'] ?? now(),
                    'participation_fee' => $record['participation_fee'] ?? '',
                    'additional_fee' => $record['additional_fee'] ?? '',
                    'capacity' => (int)($record['capacity'] ?? 0),
                    'venue' => $record['venue'] ?? '',
                    'venue_zip' => $record['venue_zip'] ?? '',
                    'venue_address' => $record['venue_address'] ?? '',
                    'tel' => $record['tel'] ?? '',
                    'email' => $record['email'] ?? '',
                    'map' => $record['map'] ?? '',
                    'status' => $record['status'] ?? '1',
                ]);
                $count++;
            }

            return $count;
        } catch (Exception $e) {
            Log::error('CSVインポートエラー: ' . $e->getMessage());
            throw $e;
        }
    }
}
