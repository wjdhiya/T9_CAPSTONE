<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Publikasi Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                        <input type="text" name="penulis" value="{{ old('penulis') }}" placeholder="Nama1, Nama2, Nama3" required class="w-full rounded-md border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                            <select name="jenis" required class="w-full rounded-md border-gray-300">
                                <option value="">Pilih Jenis</option>
                                <option value="jurnal">Jurnal</option>
                                <option value="prosiding">Prosiding</option>
                                <option value="buku">Buku</option>
                                <option value="chapter">Book Chapter</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit <span class="text-red-500">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ISSN/ISBN</label>
                            <input type="text" name="issn_isbn" value="{{ old('issn_isbn') }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                            <input type="text" name="doi" value="{{ old('doi') }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_publikasi" value="{{ old('tanggal_publikasi') }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Indexing</label>
                            <select name="indexing" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                <option value="scopus">Scopus</option>
                                <option value="wos">Web of Science</option>
                                <option value="sinta">SINTA</option>
                                <option value="google_scholar">Google Scholar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quartile (untuk Scopus/WoS)</label>
                            <select name="quartile" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                <option value="Q1">Q1</option>
                                <option value="Q2">Q2</option>
                                <option value="Q3">Q3</option>
                                <option value="Q4">Q4</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL Publikasi</label>
                        <input type="url" name="url" value="{{ old('url') }}" placeholder="https://" class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penelitian Terkait</label>
                        <select name="penelitian_id" class="w-full rounded-md border-gray-300">
                            <option value="">Tidak Ada</option>
                            @foreach($penelitianList as $p)
                                <option value="{{ $p->id }}">{{ $p->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Publikasi (PDF, max 10MB)</label>
                        <input type="file" name="file_publikasi" accept=".pdf" class="w-full">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-md border-gray-300">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('publikasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #10784b;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

