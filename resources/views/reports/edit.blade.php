<x-layouts.app title="Edit Laporan">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Edit Laporan</h1>

        @if($report->status === \App\Enums\ReportStatus::PerluDilengkapi && $report->review_note)
            <div class="alert alert-warning mb-4">
                <div>
                    <p class="font-semibold">Catatan dari Pemantau:</p>
                    <p>{{ $report->review_note }}</p>
                </div>
            </div>
        @endif

        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Sekolah <span class="text-error">*</span></span></div>
                        <select id="school_id" name="school_id"
                                class="select select-bordered @error('school_id') select-error @enderror">
                            <option value="">Pilih sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}"
                                    {{ old('school_id', $report->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Distribusi Makanan <span class="text-error">*</span></span></div>
                        <select id="distribution_id" name="distribution_id"
                                data-selected="{{ old('distribution_id', $report->distribution_id) }}"
                                class="select select-bordered @error('distribution_id') select-error @enderror">
                            <option value="">Memuat distribusi...</option>
                        </select>
                        @error('distribution_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Waktu Kejadian <span class="text-error">*</span></span></div>
                        <input type="datetime-local" name="incident_at"
                            value="{{ old('incident_at', $report->incident_at->format('Y-m-d\TH:i')) }}"
                            max="{{ now()->format('Y-m-d\TH:i') }}"
                            class="input input-bordered @error('incident_at') input-error @enderror">
                        @error('incident_at')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-4">
                        <div class="label">
                            <span class="label-text">Uraian Kejadian <span class="text-error">*</span></span>
                            <span class="label-text-alt">min. 20 karakter</span>
                        </div>
                        <textarea name="description" rows="4"
                                class="textarea textarea-bordered @error('description') textarea-error @enderror">{{ old('description', $report->description) }}</textarea>
                        @error('description')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Perkiraan Jumlah Terdampak</span></div>
                        <input type="number" name="affected_count" min="0"
                            value="{{ old('affected_count', $report->affected_count) }}"
                            class="input input-bordered @error('affected_count') input-error @enderror">
                        @error('affected_count')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-6">
                        <div class="label">
                            <span class="label-text">Bukti Pendukung</span>
                            <span class="label-text-alt">JPG, PNG, atau PDF — maks. 2 MB</span>
                        </div>
                        @if($report->evidence_path)
                            <div class="mb-2 flex items-center gap-2">
                                <span class="text-sm text-base-content/70">File saat ini:</span>
                                <a href="{{ route('reports.evidence', $report) }}"
                                class="link link-primary text-sm" target="_blank">Lihat bukti</a>
                            </div>
                        @endif
                        <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                            class="file-input file-input-bordered @error('evidence') file-input-error @enderror">
                        <div class="label"><span class="label-text-alt">Kosongkan jika tidak ingin mengganti file</span></div>
                        @error('evidence')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('reports.show', $report) }}" class="btn btn-ghost">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/report-form.js') }}"></script>
</x-layouts.app>