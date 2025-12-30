<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $owner = $this->route('owner');

        return $owner && $this->user() && $owner->id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ownerId = $this->route('owner')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_kana' => ['nullable', 'string', 'max:255'],
            'contact_zip' => ['nullable', 'string', 'max:20'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'contact_tel' => ['nullable', 'string', 'max:20', 'unique:owners,contact_tel,' . $ownerId],
            'secret_zip' => ['required', 'string', 'max:20'],
            'secret_address' => ['required', 'string', 'max:255'],
            'secret_tel' => ['required', 'string', 'max:20', 'unique:owners,secret_tel,' . $ownerId],
            'email' => ['required', 'email', 'max:255', 'unique:owners,email,' . $ownerId],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }
}
