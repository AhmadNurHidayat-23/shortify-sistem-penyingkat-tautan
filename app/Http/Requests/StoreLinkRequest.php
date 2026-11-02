<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return ['original_url' => ['bail', 'required', 'string', 'max:2048', 'url:http,https']];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'original_url.required' => 'Masukkan tautan yang ingin dipersingkat.',
            'original_url.string' => 'Tautan harus berupa teks.',
            'original_url.max' => 'Tautan tidak boleh lebih dari 2.048 karakter.',
            'original_url.url' => 'Masukkan URL yang valid dengan awalan http:// atau https://.',
        ];
    }
}
