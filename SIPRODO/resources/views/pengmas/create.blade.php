<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pengabdian Masyarakat Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pengmas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required class="w-full rounded-md border-gray-300">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mitra <span class="text-red-500">*</span></label>
                            <input type="text" name="mitra" value="{{ old('mitra') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">tahun_akademik <span class="text-red-500">*</span></label>
                            <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik', date('Y') . '/' . (date('Y') + 1)) }}" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select name="semester" required class="w-full rounded-md border-gray-300">
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta</label>
                            <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" min="0" class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full rounded-md border-gray-300">
                            <option value="proposal">Proposal</option>
                            <option value="berjalan">Berjalan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF)</label>
                            <input type="file" name="file_proposal" accept=".pdf" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF)</label>
                            <input type="file" name="file_laporan" accept=".pdf" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (JPG/PNG)</label>
                            <input type="file" name="file_dokumentasi" accept="image/*" class="w-full">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-md border-gray-300">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('pengmas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #585858;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

