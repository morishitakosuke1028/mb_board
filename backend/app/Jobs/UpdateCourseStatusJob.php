<?php

namespace App\Jobs;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCourseStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $courseIds;

    public function __construct(array $courseIds)
    {
        $this->courseIds = $courseIds;
    }

    public function handle()
    {
        Course::whereIn('id', $this->courseIds)
            ->update(['status' => 2]);
    }
}
