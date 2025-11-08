<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 管理者のみアクセス
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120']
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'CSVファイルを選択してください。',
            'file.mimes' => 'CSVファイル形式のみ対応しています。',
        ];
    }
}
