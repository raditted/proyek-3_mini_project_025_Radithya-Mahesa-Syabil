<x-layouts.app title="Tambah Distribusi">
    <div class="max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Tambah Distribusi</h1>

        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('distributions.store') }}">
                    @csrf

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Sekolah <span class="text-error">*</span></span></div>
                        <select name="school_id" class="select select-bordered @error('school_id') select-error @enderror">
                            <option value="">Pilih sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-4">
                        <div class="label"><span class="label-text">Tanggal Distribusi <span class="text-error">*</span></span></div>
                        <input type="date" name="distribution_date" value="{{ old('distribution_date') }}"
                            class="input input-bordered @error('distribution_date') input-error @enderror">
                        @error('distribution_date')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <label class="form-control mb-6">
                        <div class="label"><span class="label-text">Deskripsi Menu <span class="text-error">*</span></span></div>
                        <textarea name="menu_description" rows="3"
                                class="textarea textarea-bordered @error('menu_description') textarea-error @enderror"
                                placeholder="Contoh: Nasi, ayam goreng, sayur sop, buah jeruk">{{ old('menu_description') }}</textarea>
                        @error('menu_description')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('distributions.index') }}" class="btn btn-ghost">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>