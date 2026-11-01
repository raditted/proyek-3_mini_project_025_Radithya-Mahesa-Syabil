<x-layouts.app title="Buat Laporan">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Buat Laporan Kejadian</h1>

        <div class="card bg-base-100 shadow">
            <div class="card-body p-4 sm:p-6">
                <form class="flex flex-col" method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- sekolah --}}
                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Sekolah <span class="text-error">*</span></span></div>
                        <select id="school_id" name="school_id"
                                class="select select-bordered @error('school_id') select-error @enderror">
                            <option value="">Pilih sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    {{-- distribusi (lewat proses fetch) --}}
                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Distribusi Makanan <span class="text-error">*</span></span></div>
                        <select id="distribution_id" name="distribution_id"
                                data-selected="{{ old('distribution_id') }}"
                                class="select select-bordered @error('distribution_id') select-error @enderror">
                            <option value="">Pilih sekolah terlebih dahulu</option>
                        </select>
                        @error('distribution_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    {{-- waktu kejadian --}}
                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Waktu Kejadian <span class="text-error">*</span></span></div>
                        <input type="datetime-local" name="incident_at"
                            value="{{ old('incident_at') }}"
                            max="{{ now()->format('Y-m-d\TH:i') }}"
                            class="input input-bordered @error('incident_at') input-error @enderror">
                        @error('incident_at')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    {{-- uraian kejadian --}}
                    <label class="form-control mb-4">
                        <div class="label">
                            <span class="label-text">Uraian Kejadian <span class="text-error">*</span></span>
                            <span class="label-text-alt">min. 20 karakter</span>
                        </div>
                        <textarea name="description" rows="4"
                                class="textarea textarea-bordered @error('description') textarea-error @enderror"
                                placeholder="Ceritakan kejadian yang dialami secara singkat dan jelas...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    {{-- jumlah terdampak --}}
                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Perkiraan Jumlah Terdampak</span></div>
                        <input type="number" name="affected_count" min="0"
                            value="{{ old('affected_count') }}"
                            class="input input-bordered @error('affected_count') input-error @enderror"
                            placeholder="Opsional">
                        @error('affected_count')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    {{-- upload bukti --}}
                    <label class="form-control mb-6">
                        <div class="label">
                            <span class="label-text">Bukti Pendukung</span>
                            <span class="label-text-alt">JPG, PNG, atau PDF (Maks. 2 MB)</span>
                        </div>
                        <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                            class="file-input file-input-bordered @error('evidence') file-input-error @enderror">
                        @error('evidence')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <div class="alert alert-info mb-4 text-sm">
                        <span>Status laporan ini bukan penetapan resmi atas penyebab kejadian.</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="submit" class="btn btn-primary w-full sm:w-auto">Kirim Laporan</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-ghost w-full sm:w-auto text-center">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/report-form.js') }}"></script>
</x-layouts.app>
