<x-layouts.app title="Daftar Sekolah">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Sekolah</h1>
        <a href="{{ route('schools.create') }}" class="btn btn-primary btn-sm">+ Tambah Sekolah</a>
    </div>

    {{-- filter --}}
    <form method="GET" class="flex gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Cari nama sekolah..."
            class="input input-bordered input-sm w-full max-w-xs">
        <button class="btn btn-sm">Cari</button>
        @if(request('q'))
            <a href="{{ route('schools.index') }}" class="btn btn-sm btn-ghost">Reset</a>
        @endif
    </form>

    {{-- tabel --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Sekolah</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                        <tr>
                            <td>{{ $schools->firstItem() + $loop->index }}</td>
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->address ?? '-' }}</td>
                            <td class="flex gap-2">
                                <a href="{{ route('schools.edit', $school) }}"
                                    class="btn btn-xs btn-warning">Edit</a>

                                <form method="POST" action="{{ route('schools.destroy', $school) }}"
                                    onsubmit="return confirm('Yakin hapus sekolah ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-error">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-base-content/50">
                                Belum ada data sekolah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- pagination --}}
    <div class="mt-4">
        {{ $schools->links() }}
    </div>
</x-layouts.app>