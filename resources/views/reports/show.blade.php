<x-layouts.app title="Detail Laporan">
    <div class="max-w-3xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-bold">Detail Laporan #{{ $report->id }}</h1>
            <div class="flex gap-2">
                {{-- br-01: pelapor hanya dapat mengubah laporan miliknya saat status dikirim atau perlu_dilengkapi --}}
                @can('update', $report)
                    <a href="{{ route('reports.edit', $report) }}" class="btn btn-soft btn-sm btn-accent">Edit</a>
                @endcan
                <a href="{{ auth()->user()->isPelapor() ? route('reports.index') : route('review.reports.index') }}"
                    class="btn btn-sm btn-ghost">Kembali</a>
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
            <span class="text-xs ml-2 opacity-70">Ingat, status ini bukan penetapan resmi atas penyebab kejadian.</span>
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

        {{-- ac-06 & ac-07: panel peninjauan wewenang pemantau komunitas --}}
        @if(auth()->user()->isPemantau())
            <div class="card bg-base-100 shadow border border-primary/20 mb-6">
                <div class="card-body p-4 sm:p-6">
                    <h2 class="card-title text-base text-primary">Panel Peninjauan (Pemantau Komunitas)</h2>

                    {{-- ac-06: transisi status dari dikirim ke dalam_peninjauan --}}
                    @if($report->status === \App\Enums\ReportStatus::Dikirim)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <p class="text-sm">Laporan baru masuk dan belum ditinjau.</p>
                            <form method="POST" action="{{ route('review.reports.start', $report) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary w-full sm:w-auto">Mulai Peninjauan</button>
                            </form>
                        </div>
                    @endif

                    {{-- ac-06: transisi status dari dalam_peninjauan --}}
                    @if($report->status === \App\Enums\ReportStatus::DalamPeninjauan)
                        <div class="space-y-4">
                            <p class="text-sm text-base-content/70">Laporan sedang dalam tahap peninjauan. Pilih salah satu tindakan tindak lanjut berikut:</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- ac-06: aksi minta pelengkapan data --}}
                                <div class="rounded-lg p-3 bg-base-200/50">
                                    <h3 class="font-bold text-sm text-default mb-2">1. Minta Pelengkapan Data</h3>
                                    <form method="POST" action="{{ route('review.reports.requestCompletion', $report) }}">
                                        @csrf
                                        <textarea name="review_note" rows="2" required
                                            class="textarea textarea-bordered textarea-sm w-full mb-2"
                                            placeholder="Tulis informasi apa yang perlu dilengkapi oleh pelapor..."></textarea>
                                        <button type="submit" class="btn btn-soft btn-xs btn-error w-full">Kirim Permintaan</button>
                                    </form>
                                </div>

                                {{-- ac-06: aksi menandai untuk diteruskan --}}
                                <div class="rounded-lg p-3 bg-base-200/50">
                                    <h3 class="font-bold text-sm text-default mb-2">2. Tandai untuk Diteruskan</h3>
                                    <form method="POST" action="{{ route('review.reports.markForward', $report) }}">
                                        @csrf
                                        <textarea name="review_note" rows="2" required
                                            class="textarea textarea-bordered textarea-sm w-full mb-2"
                                            placeholder="Catatan rekomendasi penerusan ke pihak terkait..."></textarea>
                                        <button type="submit" class="btn btn-soft btn-xs btn-secondary w-full">Tandai Diteruskan</button>
                                    </form>
                                </div>
                            </div>

                            {{-- ac-06: aksi menutup laporan dari dalam_peninjauan --}}
                            <div class="pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-t border-base-200">
                                <span class="text-xs text-base-content/60">Jika laporan sudah selesai ditinjau tanpa perlu tindak lanjut:</span>
                                <form method="POST" action="{{ route('review.reports.close', $report) }}"
                                    onsubmit="return confirm('Tutup pemantauan laporan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-error btn-outline w-full sm:w-auto">Tutup Laporan</button>
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
        
        {{-- ac-07 & ac-08: panel wewenang koordinator untuk mencatat penerusan informasi --}}
        @if(auth()->user()->isKoordinator())
            <div class="card bg-base-100 shadow border border-secondary/20 mb-6">
                <div class="card-body p-4 sm:p-6">
                    <h2 class="card-title text-base text-secondary">Panel Koordinator Komunitas</h2>

                    {{-- br-06 & ac-08: aksi mencatat penerusan hanya jika status ditandai_untuk_diteruskan --}}
                    @if($report->status === \App\Enums\ReportStatus::DitandaiUntukDiteruskan)
                        <p class="text-sm text-base-content/70 mb-3">
                            Laporan ini ditandai perlu diteruskan. Catat informasi pihak yang menerima informasi ini.
                        </p>
                        <form method="POST" action="{{ route('reports.forwardings.store', $report) }}">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <label class="form-control">
                                    <div class="label"><span class="label-text text-xs">Jenis Pihak Penerima <span class="text-error">*</span></span></div>
                                    <select name="recipient_type"
                                            class="select select-bordered select-sm @error('recipient_type') select-error @enderror">
                                        <option value="">Pilih jenis</option>
                                        <option value="sekolah"             {{ old('recipient_type') === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                        <option value="sppg"                {{ old('recipient_type') === 'sppg' ? 'selected' : '' }}>SPPG</option>
                                        <option value="fasilitas_kesehatan" {{ old('recipient_type') === 'fasilitas_kesehatan' ? 'selected' : '' }}>Fasilitas Kesehatan</option>
                                        <option value="lainnya"             {{ old('recipient_type') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('recipient_type')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </label>

                                <label class="form-control">
                                    <div class="label"><span class="label-text text-xs">Nama Pihak Penerima <span class="text-error">*</span></span></div>
                                    <input type="text" name="recipient_name" value="{{ old('recipient_name') }}"
                                        placeholder="Contoh: Puskesmas Ngawi"
                                        class="input input-bordered input-sm @error('recipient_name') input-error @enderror">
                                    @error('recipient_name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                </label>
                            </div>

                            <label class="form-control mb-4">
                                <div class="label"><span class="label-text text-xs">Catatan Penerusan</span></div>
                                <textarea name="note" rows="2"
                                        class="textarea textarea-bordered textarea-sm"
                                        placeholder="Catatan tambahan terkait penerusan ini (opsional)">{{ old('note') }}</textarea>
                            </label>

                            <button type="submit" class="btn btn-sm btn-secondary w-full sm:w-auto">
                                Catat Penerusan Informasi
                            </button>
                        </form>

                    {{-- ac-06 & br-03: aksi tutup laporan hanya jika status sudah_diteruskan --}}
                    @elseif($report->status === \App\Enums\ReportStatus::SudahDiteruskan)
                        <p class="text-sm text-base-content/70 mb-3">
                            Informasi sudah diteruskan ke pihak terkait. Laporan dapat ditutup jika tidak ada tindak lanjut lebih lanjut.
                        </p>
                        <form method="POST" action="{{ route('reports.close', $report) }}"
                            onsubmit="return confirm('Tutup laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline w-full sm:w-auto">Tutup Laporan</button>
                        </form>

                    @else
                        <p class="text-xs text-base-content/60">
                            Tidak ada aksi yang tersedia untuk status laporan saat ini
                            ({{ $report->status->label() }}).
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- ac-02 & ac-05: informasi detail laporan --}}
        <div class="card bg-base-100 shadow mb-4">
            <div class="card-body p-4 sm:p-6">
                <h2 class="card-title text-base mb-4">Informasi Laporan</h2>

                <div class="grid grid-cols-1 gap-3 text-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                        <span class="font-medium sm:w-40 shrink-0 text-base-content/70">Sekolah</span>
                        <span>{{ $report->school->name }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2">
                        <span class="font-medium sm:w-40 shrink-0 text-base-content/70">Distribusi</span>
                        <div>
                            <span>{{ $report->distribution->distribution_date->format('d M Y') }} - {{ $report->distribution->menu_description }}</span>
                            @if(auth()->user()->isPemantau() || auth()->user()->isKoordinator())
                                <br>
                                <a href="{{ route('review.reports.index', ['distribution_id' => $report->distribution_id]) }}"
                                    class="link link-primary text-xs">
                                    Cari laporan lain pada distribusi ini
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                        <span class="font-medium sm:w-40 shrink-0 text-base-content/70">Waktu Kejadian</span>
                        <span>{{ $report->incident_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                        <span class="font-medium sm:w-40 shrink-0 text-base-content/70">Jumlah Terdampak</span>
                        <span>{{ $report->affected_count ? $report->affected_count . ' orang' : '-' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                        <span class="font-medium sm:w-40 shrink-0 text-base-content/70">Dikirim pada</span>
                        <span>{{ $report->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <div class="divider"></div>

                <p class="text-sm leading-relaxed">{{ $report->description }}</p>

                {{-- ac-04 & ac-05: unduh berkas bukti pendukung privat --}}
                @if($report->evidence_path)
                    <div class="mt-4">
                        <a href="{{ route('reports.evidence', $report) }}"
                            class="btn btn-sm btn-outline w-full sm:w-auto" target="_blank">
                            Lihat Bukti Pendukung
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ac-08: riwayat penerusan informasi --}}
        @if($report->forwardings->isNotEmpty())
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body p-4 sm:p-6">
                    <h2 class="card-title text-base mb-4">Riwayat Penerusan Informasi</h2>
                    @foreach($report->forwardings as $forwarding)
                        <div class="border border-base-200 rounded p-3 text-sm mb-2">
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
