<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penelitian Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('penelitian.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required class="w-full rounded-md border-gray-300 shadow-sm @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Abstrak -->
                        <div class="mb-4">
                            <label for="abstrak" class="block text-sm font-medium text-gray-700 mb-2">Abstrak <span class="text-red-500">*</span></label>
                            <textarea name="abstrak" id="abstrak" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm @error('abstrak') border-red-500 @enderror">{{ old('abstrak') }}</textarea>
                            @error('abstrak')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis & tahun_akademik -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Penelitian <span class="text-red-500">*</span></label>
                                <select name="jenis" id="jenis" required class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Pilih Jenis</option>
                                    <option value="mandiri" {{ old('jenis') == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    <option value="hibah_internal" {{ old('jenis') == 'hibah_internal' ? 'selected' : '' }}>Hibah Internal</option>
                                    <option value="hibah_eksternal" {{ old('jenis') == 'hibah_eksternal' ? 'selected' : '' }}>Hibah Eksternal</option>
                                    <option value="kerjasama" {{ old('jenis') == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                                </select>
                            </div>
                            <div>
                                <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik <span class="text-red-500">*</span></label>
                                <input type="text" name="tahun_akademik" id="tahun_akademik" value="{{ old('tahun_akademik', date('Y') . '/' . (date('Y') + 1)) }}" placeholder="2024/2025" required class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Semester & Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                                <select name="semester" id="semester" required class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Dana & Sumber Dana -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="dana" class="block text-sm font-medium text-gray-700 mb-2">Dana (Rp)</label>
                                <input type="number" name="dana" id="dana" value="{{ old('dana') }}" min="0" class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                                <input type="text" name="sumber_dana" id="sumber_dana" value="{{ old('sumber_dana') }}" placeholder="Contoh: DIKTI, Internal, dll" class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="proposal" {{ old('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="berjalan" {{ old('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <!-- File Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="file_proposal" class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                <input type="file" name="file_proposal" id="file_proposal" accept=".pdf" class="w-full">
                                <p class="mt-1 text-xs text-gray-500">Format: PDF, Maksimal 10MB</p>
                            </div>
                            <div>
                                <label for="file_laporan" class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF, max 10MB)</label>
                                <input type="file" name="file_laporan" id="file_laporan" accept=".pdf" class="w-full">
                                <p class="mt-1 text-xs text-gray-500">Format: PDF, Maksimal 10MB</p>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-md border-gray-300 shadow-sm">{{ old('catatan') }}</textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('penelitian.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition" style="background-color: #a02127;">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

