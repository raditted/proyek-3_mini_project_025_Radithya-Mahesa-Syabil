<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isKoordinator();
    }

    public function rules(): array
    {
        return [
            'school_id'         => ['required', 'exists:schools,id'],
            'distribution_date' => ['required', 'date'],
            'menu_description'  => ['required', 'string', 'max:500'],
        ];
    }
}
