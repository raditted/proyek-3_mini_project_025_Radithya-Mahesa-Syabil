<x-layouts.app title="Detail Laporan">
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Detail Laporan #{{ $report->id }}</h1>
            <div class="flex gap-2">
                @can('update', $report)
                    <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">Edit</a>
                @endcan
                <a href="{{ auth()->user()->isPelapor() ? route('reports.index') : route('review.reports.index') }}"
                class="btn btn-sm btn-ghost">← Kembali</a>
            </div>
        </div>

        {{-- Status --}}
        <div class="alert mb-4
            {{ $report->status === \App\Enums\ReportStatus::Dikirim ? 'alert-info' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::DalamPeninjauan ? 'alert-warning' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::PerluDilengkapi ? 'alert-error' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::DitandaiUntukDiteruskan ? 'alert-warning' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::SudahDiteruskan ? 'alert-success' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::Ditutup ? '' : '' }}
        ">
            <span class="font-semibold">Status: {{ $report->status->label() }}</span>
            <span class="text-xs ml-2 opacity-70">— Status ini bukan penetapan resmi atas penyebab kejadian.</span>
        </div>

        {{-- catatan dari pemantau --}}
        @if($report->review_note)
            <div class="alert alert-warning mb-4">
                <div>
                    <p class="font-semibold text-sm">Catatan Pemantau:</p>
                    <p class="text-sm">{{ $report->review_note }}</p>
                </div>
            </div>
        @endif

        {{-- info laporan --}}
        <div class="card bg-base-100 shadow mb-4">
            <div class="card-body">
                <h2 class="card-title text-base mb-4">Informasi Laporan</h2>

                <div class="grid grid-cols-1 gap-3 text-sm">
                    <div class="flex gap-2">
                        <span class="font-medium w-40 shrink-0">Sekolah</span>
                        <span>{{ $report->school->name }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium w-40 shrink-0">Distribusi</span>
                        <span>{{ $report->distribution->distribution_date->format('d M Y') }} — {{ $report->distribution->menu_description }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium w-40 shrink-0">Waktu Kejadian</span>
                        <span>{{ $report->incident_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium w-40 shrink-0">Jumlah Terdampak</span>
                        <span>{{ $report->affected_count ?? '-' }} orang</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium w-40 shrink-0">Dikirim pada</span>
                        <span>{{ $report->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <div class="divider"></div>

                <p class="text-sm leading-relaxed">{{ $report->description }}</p>

                @if($report->evidence_path)
                    <div class="mt-4">
                        <a href="{{ route('reports.evidence', $report) }}"
                            class="btn btn-sm btn-outline" target="_blank">
                            Lihat Bukti Pendukung
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- penerusan (jika ada) --}}
        @if($report->forwardings->isNotEmpty())
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title text-base mb-4">Riwayat Penerusan</h2>
                    @foreach($report->forwardings as $forwarding)
                        <div class="border rounded p-3 text-sm mb-2">
                            <p><span class="font-medium">Penerima:</span> {{ $forwarding->recipient_name }} ({{ $forwarding->recipient_type }})</p>
                            @if($forwarding->note)
                                <p><span class="font-medium">Catatan:</span> {{ $forwarding->note }}</p>
                            @endif
                            <p class="text-base-content/60 mt-1">{{ $forwarding->forwarded_at->format('d M Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>