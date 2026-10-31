<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isKoordinator();
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }
}
