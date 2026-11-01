<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPelapor();
    }

    // ac-03: validation - data laporan tidak valid ditolak dan tidak tersimpan
    public function rules(): array
    {
        return [
            'school_id'       => ['required', 'exists:schools,id'],
            'distribution_id' => ['required', 'exists:distributions,id'],
            'incident_at'     => ['required', 'date', 'before_or_equal:now'],
            'description'     => ['required', 'string', 'min:20', 'max:2000'],
            'affected_count'  => ['nullable', 'integer', 'min:0'],
            // ac-04: evidence - file valid (jpg, png, pdf maks 2 mb) dapat diunggah, file tidak valid ditolak
            'evidence'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validateDistributionBelongsToSchool($validator);
                $this->validateIncidentAfterDistribution($validator);
                $this->validateNoDuplicateReport($validator);
            },
        ];
    }

    // br-02: distribusi yang dipilih harus milik sekolah yang dipilih, server wajib memeriksa ini
    private function validateDistributionBelongsToSchool(Validator $validator): void
    {
        if ($this->filled(['school_id', 'distribution_id'])) {
            $valid = \App\Models\Distribution::where('id', $this->distribution_id)
                ->where('school_id', $this->school_id)
                ->exists();

            if (! $valid) {
                $validator->errors()->add(
                    'distribution_id',
                    'Distribusi yang dipilih tidak sesuai dengan sekolah yang dipilih. (BR-02)'
                );
            }
        }
    }

    // br-04: incident_at tidak boleh di masa depan dan tidak boleh lebih awal dari distribution_date
    private function validateIncidentAfterDistribution(Validator $validator): void
    {
        if ($this->filled(['distribution_id', 'incident_at'])) {
            $distribution = \App\Models\Distribution::find($this->distribution_id);
            if ($distribution && $this->incident_at < $distribution->distribution_date->toDateString()) {
                $validator->errors()->add(
                    'incident_at',
                    'Waktu kejadian tidak boleh lebih awal dari tanggal distribusi (' . $distribution->distribution_date->format('d M Y') . '). (BR-04)'
                );
            }
        }
    }

    // br-05: satu pelapor hanya dapat membuat satu laporan untuk satu distribusi
    private function validateNoDuplicateReport(Validator $validator): void
    {
        if ($this->filled('distribution_id')) {
            $exists = \App\Models\Report::where('user_id', $this->user()->id)
                ->where('distribution_id', $this->distribution_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'distribution_id',
                    'Anda sudah membuat laporan untuk distribusi ini sebelumnya. (BR-05)'
                );
            }
        }
    }
}
