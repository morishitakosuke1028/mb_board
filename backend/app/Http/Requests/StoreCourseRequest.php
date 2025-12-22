<?php

namespace App\Http\Requests;

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
        if ($this->isAdminRequest()) {
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

        return [
            'course_title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'date_time' => ['required', 'date'],
            'participation_fee' => ['nullable', 'string', 'max:255'],
            'additional_fee' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'venue_zip' => ['nullable', 'string', 'max:20'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
            'course_image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:3072'],
            'instructor' => ['nullable', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'map' => ['nullable', 'string'],
        ];
    }

    private function isAdminRequest(): bool
    {
        if ($this->user('admin')) {
            return true;
        }

        $prefix = $this->route()?->getPrefix();
        return is_string($prefix) && str_contains($prefix, 'admin');
    }
}
