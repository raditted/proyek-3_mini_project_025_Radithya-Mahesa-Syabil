<x-layouts.app title="Detail Laporan">
    <div class="max-w-3xl">
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

        {{-- status banner --}}
        <div class="alert mb-4
            {{ $report->status === \App\Enums\ReportStatus::Dikirim ? 'alert-info' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::DalamPeninjauan ? 'alert-warning' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::PerluDilengkapi ? 'alert-error' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::DitandaiUntukDiteruskan ? 'alert-warning' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::SudahDiteruskan ? 'alert-success' : '' }}
            {{ $report->status === \App\Enums\ReportStatus::Ditutup ? 'alert-neutral' : '' }}
        ">
            <span class="font-semibold">Status: {{ $report->status->label() }}</span>
            <span class="text-xs ml-2 opacity-70">— Status ini bukan penetapan resmi atas penyebab kejadian.</span>
        </div>

        {{-- catatan pemantau sebelumnya --}}
        @if($report->review_note)
            <div class="alert alert-warning mb-4">
                <div>
                    <p class="font-semibold text-sm">Catatan Pemantau:</p>
                    <p class="text-sm">{{ $report->review_note }}</p>
                </div>
            </div>
        @endif

        {{-- note: action untuk pemantau --}}
        @if(auth()->user()->isPemantau())
            <div class="card bg-base-100 shadow border border-primary/20 mb-6">
                <div class="card-body">
                    <h2 class="card-title text-base text-primary">Panel Peninjauan (Pemantau Komunitas)</h2>

                    {{-- Transisi ketika dikirim -> dalam_peninjauan --}}
                    @if($report->status === \App\Enums\ReportStatus::Dikirim)
                        <div class="flex items-center justify-between">
                            <p class="text-sm">Laporan baru masuk dan belum ditinjau.</p>
                            <form method="POST" action="{{ route('review.reports.start', $report) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Mulai Peninjauan</button>
                            </form>
                        </div>
                    @endif

                    {{-- transisi dari dalam_peninjauan --}}
                    @if($report->status === \App\Enums\ReportStatus::DalamPeninjauan)
                        <div class="space-y-4">
                            <p class="text-sm text-base-content/70">Laporan sedang dalam tahap peninjauan. Pilih salah satu tindakan tindak lanjut berikut:</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Aksi: Minta Pelengkapan --}}
                                <div class="border rounded-lg p-3 bg-base-200/50">
                                    <h3 class="font-bold text-sm text-error mb-2">1. Minta Pelengkapan Data</h3>
                                    <form method="POST" action="{{ route('review.reports.requestCompletion', $report) }}">
                                        @csrf
                                        <textarea name="review_note" rows="2" required
                                            class="textarea textarea-bordered textarea-sm w-full mb-2"
                                            placeholder="Tulis informasi apa yang perlu dilengkapi oleh pelapor..."></textarea>
                                        <button type="submit" class="btn btn-xs btn-error w-full">Kirim Permintaan</button>
                                    </form>
                                </div>

                                {{-- aksi untuk menandai untuk diteruskan --}}
                                <div class="border rounded-lg p-3 bg-base-200/50">
                                    <h3 class="font-bold text-sm text-secondary mb-2">2. Tandai untuk Diteruskan</h3>
                                    <form method="POST" action="{{ route('review.reports.markForward', $report) }}">
                                        @csrf
                                        <textarea name="review_note" rows="2" required
                                            class="textarea textarea-bordered textarea-sm w-full mb-2"
                                            placeholder="Catatan rekomendasi penerusan ke pihak terkait..."></textarea>
                                        <button type="submit" class="btn btn-xs btn-secondary w-full">Tandai Diteruskan</button>
                                    </form>
                                </div>
                            </div>

                            {{-- aksi untuk menutup Laporan --}}
                            <div class="border-t pt-3 flex items-center justify-between">
                                <span class="text-xs text-base-content/60">Jika laporan sudah selesai ditinjau tanpa perlu tindak lanjut:</span>
                                <form method="POST" action="{{ route('review.reports.close', $report) }}"
                                    onsubmit="return confirm('Tutup pemantauan laporan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline">Tutup Laporan</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if(!in_array($report->status, [\App\Enums\ReportStatus::Dikirim, \App\Enums\ReportStatus::DalamPeninjauan]))
                        <p class="text-xs text-base-content/60">Tidak ada aksi peninjauan yang tersedia untuk status saat ini.</p>
                    @endif
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
                        <div>
                            <span>{{ $report->distribution->distribution_date->format('d M Y') }} — {{ $report->distribution->menu_description }}</span>
                            @if(auth()->user()->isPemantau() || auth()->user()->isKoordinator())
                                <br>
                                <a href="{{ route('review.reports.index', ['distribution_id' => $report->distribution_id]) }}"
                                    class="link link-primary text-xs">
                                    Cari laporan lain pada distribusi ini
                                </a>
                            @endif
                        </div>
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

        {{-- riwayat penerusan (jika ada) --}}
        @if($report->forwardings->isNotEmpty())
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title text-base mb-4">Riwayat Penerusan Informasi</h2>
                    @foreach($report->forwardings as $forwarding)
                        <div class="border rounded p-3 text-sm mb-2">
                            <p><span class="font-medium">Penerima:</span> {{ $forwarding->recipient_name }} ({{ $forwarding->recipient_type }})</p>
                            @if($forwarding->note)
                                <p><span class="font-medium">Catatan:</span> {{ $forwarding->note }}</p>
                            @endif
                            <p class="text-base-content/60 mt-1">Dicatat pada: {{ $forwarding->forwarded_at->format('d M Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>