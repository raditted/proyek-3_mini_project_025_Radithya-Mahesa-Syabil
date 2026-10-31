<x-layouts.app title="Tambah Sekolah">
    <div class="max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Tambah Sekolah</h1>

        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('schools.store') }}">
                    @csrf

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Nama Sekolah <span class="text-error">*</span></span></div>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="input input-bordered @error('name') input-error @enderror"
                            placeholder="Contoh: SMP Harapan Bangsa">
                        @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-6">
                        <div class="label"><span class="label-text">Alamat</span></div>
                        <textarea name="address" rows="2"
                                class="textarea textarea-bordered @error('address') textarea-error @enderror"
                                placeholder="Alamat sekolah (opsional)">{{ old('address') }}</textarea>
                        @error('address')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('schools.index') }}" class="btn btn-ghost">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>