<x-layouts.app title="Laporan Saya">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">Laporan Saya</h1>
        <a href="{{ route('reports.create') }}" class="btn btn-soft btn-primary btn-sm sm:btn-md">+ Buat Laporan</a>
    </div>

    {{-- filter status --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <select name="status" class="select select-bordered select-sm w-full sm:w-auto">
            <option value="">Semua Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-sm">Filter</button>
        @if(request('status'))
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-ghost">Reset</a>
        @endif
    </form>

    <div class="card bg-base-100 shadow">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sekolah</th>
                        <th>Distribusi</th>
                        <th>Waktu Kejadian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $reports->firstItem() + $loop->index }}</td>
                            <td>{{ $report->school->name }}</td>
                            <td>{{ $report->distribution->distribution_date->format('d M Y') }}</td>
                            <td>{{ $report->incident_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="badge badge-soft badge-md
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
                            <td class="flex gap-2">
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-soft btn-sm btn-info">Lihat</a>
                                @can('update', $report)
                                    <a href="{{ route('reports.edit', $report) }}" class="btn btn-soft btn-sm btn-accent">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-base-content/50 py-6">
                                Belum ada laporan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $reports->links() }}</div>
</x-layouts.app>
