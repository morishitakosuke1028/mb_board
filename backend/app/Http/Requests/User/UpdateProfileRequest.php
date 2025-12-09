<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user && $this->user() && $user->id === $this->user()->id;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:10'],
            'address' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:20', 'unique:users,tel,' . $userId],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }
}
