<?php
use Illuminate\Support\Facades\Schedule;
use App\Models\Course;
use App\Jobs\UpdateCourseStatusJob;
use Illuminate\Support\Facades\Log;

Schedule::call(function () {

    // date_time が過去で status が未終了のもの
    $expired = Course::where('date_time', '<', now())
        ->where('status', '!=', 2)
        ->pluck('id')
        ->toArray();

    if (!empty($expired)) {
        foreach (array_chunk($expired, 500) as $chunk) {
            UpdateCourseStatusJob::dispatch($chunk);
        }
    }

})->dailyAt("20:59");

