<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Publikasi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('publikasi.update', $publikasi) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $publikasi->judul) }}" required class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                        <input type="text" name="penulis" value="{{ old('penulis', $publikasi->penulis) }}" required class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                            <select name="jenis" required class="w-full rounded-md border-gray-300">
                                <option value="jurnal" {{ $publikasi->jenis == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                                <option value="prosiding" {{ $publikasi->jenis == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                                <option value="buku" {{ $publikasi->jenis == 'buku' ? 'selected' : '' }}>Buku</option>
                                <option value="chapter" {{ $publikasi->jenis == 'chapter' ? 'selected' : '' }}>Book Chapter</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit <span class="text-red-500">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit', $publikasi->penerbit) }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ISSN/ISBN</label>
                            <input type="text" name="issn_isbn" value="{{ old('issn_isbn', $publikasi->issn_isbn) }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                            <input type="text" name="doi" value="{{ old('doi', $publikasi->doi) }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_publikasi" value="{{ old('tanggal_publikasi', $publikasi->tanggal_publikasi?->format('Y-m-d')) }}" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Indexing</label>
                            <select name="indexing" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                <option value="scopus" {{ $publikasi->indexing == 'scopus' ? 'selected' : '' }}>Scopus</option>
                                <option value="wos" {{ $publikasi->indexing == 'wos' ? 'selected' : '' }}>Web of Science</option>
                                <option value="sinta" {{ $publikasi->indexing == 'sinta' ? 'selected' : '' }}>SINTA</option>
                                <option value="google_scholar" {{ $publikasi->indexing == 'google_scholar' ? 'selected' : '' }}>Google Scholar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quartile</label>
                            <select name="quartile" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                <option value="Q1" {{ $publikasi->quartile == 'Q1' ? 'selected' : '' }}>Q1</option>
                                <option value="Q2" {{ $publikasi->quartile == 'Q2' ? 'selected' : '' }}>Q2</option>
                                <option value="Q3" {{ $publikasi->quartile == 'Q3' ? 'selected' : '' }}>Q3</option>
                                <option value="Q4" {{ $publikasi->quartile == 'Q4' ? 'selected' : '' }}>Q4</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Publikasi (PDF)</label>
                        @if($publikasi->file_publikasi)
                            <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ Storage::url($publikasi->file_publikasi) }}" target="_blank" class="text-blue-600">Lihat File</a></p>
                        @endif
                        <input type="file" name="file_publikasi" accept=".pdf" class="w-full">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full rounded-md border-gray-300">{{ old('catatan', $publikasi->catatan) }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('publikasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #10784b;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

