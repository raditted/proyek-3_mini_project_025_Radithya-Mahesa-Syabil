<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForwardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isKoordinator();
    }

    // br-06: memastikan data penerusan lengkap (jenis dan nama penerima wajib diisi)
    public function rules(): array
    {
        return [
            'recipient_type' => ['required', 'in:sekolah,sppg,fasilitas_kesehatan,lainnya'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_type.required' => 'Jenis pihak penerima wajib dipilih.',
            'recipient_type.in' => 'Jenis pihak penerima tidak valid.',
            'recipient_name.required' => 'Nama pihak penerima wajib diisi.',
        ];
    }
}
