<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'exists:owners,id'],
            'course_title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'course_image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:3072'],
            'instructor' => ['required', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'date_time' => ['required', 'date'],
            'participation_fee' => ['required', 'string', 'max:255'],
            'additional_fee' => ['nullable', 'string', 'max:255'],
            'capacity' => ['required', 'integer'],
            'venue' => ['required', 'string', 'max:255'],
            'venue_zip' => ['required', 'string', 'max:10'],
            'venue_address' => ['required', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'map' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ];
    }
}
