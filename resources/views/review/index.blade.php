<x-layouts.app title="Tinjau Laporan">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Daftar Laporan Masuk</h1>
            <p class="text-sm text-base-content/60">Tinjau kelengkapan laporan dan pantau keterkaitan kejadian</p>
        </div>
    </div>

    {{-- ac-10: banner filter distribusi yang sama --}}
    @if($selectedDistribution)
        <div class="alert alert-info mb-4 flex flex-col sm:flex-row justify-between gap-2">
            <div>
                <p class="font-bold">Menampilkan laporan terkait distribusi yang sama:</p>
                <p class="text-sm">{{ $selectedDistribution->school->name }} - {{ $selectedDistribution->distribution_date->format('d M Y') }} ({{ $selectedDistribution->menu_description }})</p>
            </div>
            <a href="{{ route('review.reports.index') }}" class="btn btn-sm btn-ghost w-full sm:w-auto">Tampilkan Semua Laporan</a>
        </div>
    @endif

    {{-- ac-10: filter status, sekolah, dan pencarian kata kunci --}}
    <form method="GET" class="card bg-base-100 shadow-sm p-4 mb-4">
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
                <span class="label-text text-xs mb-1">Status Laporan</span>
                <select name="status" class="select select-bordered select-sm w-full sm:w-auto">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control flex-1 min-w-[180px]">
                <span class="label-text text-xs mb-1">Cari Kata Kunci</span>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari uraian kejadian..."
                    class="input input-bordered input-sm w-full">
            </div>

            <div class="flex gap-2 mt-2 sm:mt-0">
                <button type="submit" class="btn btn-soft btn-sm btn-primary">Filter</button>
                @if(request()->hasAny(['school_id', 'status', 'distribution_id', 'q']))
                    <a href="{{ route('review.reports.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                @endif
            </div>
        </div>
    </form>

    {{-- ac-06 & ac-07: tabel daftar peninjauan laporan untuk pemantau dan koordinator --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu Kejadian</th>
                        <th>Sekolah & Distribusi</th>
                        <th>Uraian Singkat</th>
                        <th>Terdampak</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td>{{ $report->incident_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="font-bold">{{ $report->school->name }}</div>
                                <div class="text-xs text-base-content/60">
                                    {{ $report->distribution->distribution_date->format('d M Y') }} - {{ $report->distribution->menu_description }}
                                </div>
                                {{-- ac-10: tautan filter laporan lain pada distribusi yang sama --}}
                                <a href="{{ route('review.reports.index', ['distribution_id' => $report->distribution_id]) }}"
                                    class="link link-primary text-xs mt-1 inline-block">
                                    Laporan lain di distribusi ini
                                </a>
                            </td>
                            <td class="max-w-xs">
                                <p class="truncate">{{ $report->description }}</p>
                            </td>
                            <td>{{ $report->affected_count ? $report->affected_count . ' orang' : '-' }}</td>
                            <td>
                                <span class="badge badge-soft badge-sm
                                    {{ $report->status === \App\Enums\ReportStatus::Dikirim ? 'badge-info' : '' }}
                                    {{ $report->status === \App\Enums\ReportStatus::DalamPeninjauan ? 'badge-warning' : '' }}
                                    {{ $report->status === \App\Enums\ReportStatus::PerluDilengkapi ? 'badge-error' : '' }}
                                    {{ $report->status === \App\Enums\ReportStatus::DitandaiUntukDiteruskan ? 'badge-secondary' : '' }}
                                    {{ $report->status === \App\Enums\ReportStatus::SudahDiteruskan ? 'badge-accent' : '' }}
                                    {{ $report->status === \App\Enums\ReportStatus::Ditutup ? 'badge-neutral' : '' }}
                                ">
                                    {{ $report->status->label() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-xs btn-outline btn-info">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-base-content/50 py-6">
                                Tidak ada laporan yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ac-11: pagination mempertahankan filter aktif --}}
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</x-layouts.app>
