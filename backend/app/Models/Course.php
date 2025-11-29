<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        if ($image instanceof UploadedFile) {
            $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('courses', $filename, 'public');
            $data['course_image'] = Storage::url($path);
        }

        return self::create($data);
    }

    public function updateWithImage(array $data, ?UploadedFile $image = null): void
    {
        if ($image instanceof UploadedFile) {
            if (!empty($this->course_image)) {
                $oldPath = str_replace('/storage/', '', $this->course_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('courses', $filename, 'public');
            $data['course_image'] = Storage::url($path);
        }
        $this->update($data);
    }

    public static function createFromCsvRecord(array $record): self
    {
        return self::create([
            'owner_id'         => $record['owner_id'] ?? null,
            'course_title'     => $record['course_title'] ?? '',
            'content'          => $record['content'] ?? '',
            'instructor'       => $record['instructor'] ?? '',
            'instructor_title' => $record['instructor_title'] ?? '',
            'date_time'        => $record['date_time'] ?? now(),
            'participation_fee'=> $record['participation_fee'] ?? '',
            'additional_fee'   => $record['additional_fee'] ?? '',
            'capacity'         => (int)($record['capacity'] ?? 0),
            'venue'            => $record['venue'] ?? '',
            'venue_zip'        => $record['venue_zip'] ?? '',
            'venue_address'    => $record['venue_address'] ?? '',
            'tel'              => $record['tel'] ?? '',
            'email'            => $record['email'] ?? '',
            'map'              => $record['map'] ?? '',
            'status'           => $record['status'] ?? '1',
        ]);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'attendances')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
