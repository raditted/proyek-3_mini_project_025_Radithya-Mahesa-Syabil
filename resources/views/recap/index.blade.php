<x-layouts.app title="Rekap Laporan">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Rekap Laporan</h1>
        <p class="text-sm text-base-content/60">Ringkasan kondisi laporan keamanan makanan MBG</p>
    </div>

    {{-- filter --}}
    <form method="GET" class="card bg-base-100 shadow-sm p-4 mb-6">
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 items-stretch sm:items-end">
            <div class="form-control">
                <span class="label-text text-xs mb-1">Sekolah</span>
                <select name="school_id" class="select select-bordered select-sm w-full sm:w-auto">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control">
                <span class="label-text text-xs mb-1">Status</span>
                <select name="status" class="select select-bordered select-sm w-full sm:w-auto">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 mt-2 sm:mt-0">
                <button type="submit" class="btn btn-soft btn-sm btn-primary">Terapkan Filter</button>
                @if(request()->hasAny(['school_id', 'status']))
                    <a href="{{ route('recap.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                @endif
            </div>
        </div>
    </form>

    {{-- ringkasan--}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-title">Total Laporan</div>
            <div class="stat-value text-info">{{ $totalReports }}</div>
            <div class="stat-desc">Semua laporan yang masuk</div>
        </div>

        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-title">Masih Diproses</div>
            <div class="stat-value text-warning">{{ $openReports }}</div>
            <div class="stat-desc">Belum ditutup</div>
        </div>

        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-title">Selesai</div>
            <div class="stat-value text-success">{{ $closedReports }}</div>
            <div class="stat-desc">Sudah ditutup</div>
        </div>
    </div>

    {{-- tabel rekap per sekolah --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-4 sm:p-6">
            <h2 class="card-title text-base mb-4">Rekap per Sekolah</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead>
                        <tr>
                            <th>Sekolah</th>
                            <th class="text-center">Total Laporan</th>
                            <th class="text-center">Masih Diproses</th>
                            <th class="text-center">Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolRecap as $school)
                            <tr>
                                <td>{{ $school->name }}</td>
                                <td class="text-center">{{ $school->total_reports }}</td>
                                <td class="text-center">
                                    <span class="{{ $school->open_reports > 0 ? 'text-warning font-bold' : '' }}">
                                        {{ $school->open_reports }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $school->closed_reports }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-xs text-base-content/50 mt-4">
        Angka menunjukkan jumlah laporan yang masuk berdasarkan data aktual aplikasi.
    </p>
</x-layouts.app>
