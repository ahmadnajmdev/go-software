<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service' => ['nullable', 'string', 'max:60'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'], // honeypot
        ];
    }
}
