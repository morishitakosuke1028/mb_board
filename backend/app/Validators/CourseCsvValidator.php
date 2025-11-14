<?php
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class CourseCsvValidator
{
    public static function validate(array $record, int $line)
    {
        $rules = [
            'owner_id'         => ['required', 'integer'],
            'course_title'     => ['required'],
            'date_time'        => ['required', 'date'],
            'email'            => ['nullable', 'email'],
            'capacity'         => ['nullable', 'integer'],
            'status'           => ['required', 'in:0,1'],
        ];

        $messages = [
            'owner_id.required' => "{$line}行目: owner_id は必須です。",
            'course_title.required' => "{$line}行目: course_title は必須です。",
            'date_time.date' => "{$line}行目: date_time は日付形式で入力してください。",
            'status.in' => "{$line}行目: status は 0 または 1 を指定してください。",
        ];

        return Validator::make($record, $rules, $messages);
    }
}
