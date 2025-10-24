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

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
