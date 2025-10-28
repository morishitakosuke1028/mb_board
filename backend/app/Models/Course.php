<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'course_title',
        'content',
        'course_image',
        'instructor',
        'instructor_title',
        'date_time',
        'participation_fee',
        'additional_fee',
        'capacity',
        'venue',
        'venue_zip',
        'venue_address',
        'tel',
        'email',
        'map',
        'status',
    ];

    public static function createWithImage(array $data, ?UploadedFile $image): self
    {
        // 画像がある場合のみ保存
        if ($image instanceof UploadedFile) {
            $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('courses', $filename, 'public');
            $data['course_image'] = Storage::url($path);
        }
        return self::create($data);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
