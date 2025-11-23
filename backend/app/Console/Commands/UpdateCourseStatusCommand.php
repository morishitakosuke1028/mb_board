<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use Illuminate\Support\Facades\Log;

class UpdateCourseStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * 実行コマンド名
     */
    protected $signature = 'course:update-status';

    /**
     * The console command description.
     */
    protected $description = '開催期限が過ぎたコースのステータスを 2（終了）に更新する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Course::where('date_time', '<', now())
            ->where('status', '!=', 2)
            ->get();

        if ($expired->isEmpty()) {
            $this->info('更新対象のコースはありませんでした。');
            return Command::SUCCESS;
        }

        $count = $expired->count();

        Course::whereIn('id', $expired->pluck('id'))
            ->update(['status' => 2]);

        $this->info("{$count} 件のコースをステータス 2（終了）に更新しました。");

        Log::info('course:update-status executed', [
            'updated_count' => $count
        ]);

        return Command::SUCCESS;
    }
}
