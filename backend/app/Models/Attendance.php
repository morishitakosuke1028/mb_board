<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AttendanceHistory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function recordDeletionHistory(?string $message = null): void
    {
        AttendanceHistory::create([
            'attendance_id' => $this->id,
            'user_id' => $this->user_id,
            'message' => $message,
            'deleted_at' => now(),
        ]);
    }
}
