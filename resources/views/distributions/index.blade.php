<x-layouts.app title="Daftar Distribusi">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">Daftar Distribusi</h1>
        <a href="{{ route('distributions.create') }}" class="btn btn-soft btn-primary btn-sm sm:btn-md">+ Tambah Distribusi</a>
    </div>

    {{-- ac-10: filter sekolah dan pencarian menu distribusi --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <select name="school_id" class="select select-bordered select-sm w-full sm:w-auto">
            <option value="">Semua Sekolah</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
        <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Cari menu..."
            class="input input-bordered input-sm w-full sm:max-w-xs">
        <button class="btn btn-soft btn-primary btn-sm">Filter</button>
        @if(request()->hasAny(['school_id', 'q']))
            <a href="{{ route('distributions.index') }}" class="btn btn-sm btn-ghost">Reset</a>
        @endif
    </form>

    {{-- tabel daftar distribusi --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sekolah</th>
                        <th>Tanggal</th>
                        <th>Menu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions as $dist)
                        <tr>
                            <td>{{ $distributions->firstItem() + $loop->index }}</td>
                            <td>{{ $dist->school->name }}</td>
                            <td>{{ $dist->distribution_date->format('d M Y') }}</td>
                            <td class="max-w-xs truncate">{{ $dist->menu_description }}</td>
                            <td class="flex gap-2">
                                <a href="{{ route('distributions.edit', $dist) }}"
                                    class="btn btn-soft btn-sm btn-warning">Edit</a>

                                <form method="POST" action="{{ route('distributions.destroy', $dist) }}"
                                    onsubmit="return confirm('Yakin hapus distribusi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-soft btn-sm btn-error">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-base-content/50 py-6">
                                Belum ada data distribusi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ac-11: pagination mempertahankan filter aktif --}}
    <div class="mt-4">
        {{ $distributions->links() }}
    </div>
</x-layouts.app>
