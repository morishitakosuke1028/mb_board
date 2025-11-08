<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;

class CourseImportService
{
    public function import(string $filePath): int
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $records = (new Statement())->process($csv);

        $count = 0;

        DB::transaction(function () use ($records, &$count) {
            foreach ($records as $row) {
                try {
                    Course::create([
                        'owner_id' => $row['owner_id'] ?? null,
                        'course_title' => $row['course_title'] ?? '',
                        'content' => $row['content'] ?? '',
                        'instructor' => $row['instructor'] ?? '',
                        'instructor_title' => $row['instructor_title'] ?? '',
                        'date_time' => $row['date_time'] ?? null,
                        'participation_fee' => $row['participation_fee'] ?? '',
                        'additional_fee' => $row['additional_fee'] ?? '',
                        'capacity' => $row['capacity'] ?? 0,
                        'venue' => $row['venue'] ?? '',
                        'venue_zip' => $row['venue_zip'] ?? '',
                        'venue_address' => $row['venue_address'] ?? '',
                        'tel' => $row['tel'] ?? '',
                        'email' => $row['email'] ?? '',
                        'map' => $row['map'] ?? '',
                        'status' => $row['status'] ?? 0,
                    ]);
                    $count++;
                } catch (\Throwable $e) {
                    Log::error('Import error', [
                        'row' => $row,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        });

        return $count;
    }
}
