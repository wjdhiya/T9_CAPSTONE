<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Publikasi Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                {{-- Form untuk Store Publikasi --}}
                <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Judul --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required 
                               class="w-full rounded-md border-gray-300">
                    </div>

                    {{-- Penulis --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penulis (Nama Peneliti/Dosen) <span class="text-red-500">*</span></label>
                        <input type="text" name="penulis" value="{{ old('penulis') }}" placeholder="Nama Dosen 1, Nama Dosen 2, ..." required 
                               class="w-full rounded-md border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">Pisahkan nama dengan koma. (Sesuai dengan kolom penulis di database Anda)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        {{-- Jenis Publikasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                            <select name="jenis" required 
                                    class="w-full rounded-md border-gray-300">
                                <option value="">Pilih Jenis</option>
                                {{-- Nilai harus sesuai dengan ENUM di migration --}}
                                @foreach (['jurnal', 'prosiding', 'buku', 'book_chapter', 'paten', 'hki'] as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis') == $jenis ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Penerbit --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit/Nama Jurnal <span class="text-red-500">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit') }}" required 
                                   class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        {{-- Tanggal Terbit (Konsisten dengan migration) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_terbit" value="{{ old('tanggal_terbit') }}" required 
                                   class="w-full rounded-md border-gray-300">
                        </div>

                        {{-- Tahun Akademik (Required) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_akademik" value="{{ old('tahun_akademik', date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}" 
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        
                        {{-- Semester (Required) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                            <select name="semester" required 
                                    class="w-full rounded-md border-gray-300">
                                <option value="">Pilih Semester</option>
                                <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        {{-- ISSN/ISBN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ISSN/ISBN</label>
                            <input type="text" name="issn_isbn" value="{{ old('issn_isbn') }}" 
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        {{-- DOI --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                            <input type="text" name="doi" value="{{ old('doi') }}" 
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        {{-- URL --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL Publikasi</label>
                            <input type="url" name="url" value="{{ old('url') }}" placeholder="https://" 
                                   class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        {{-- Indexing (Sesuai dengan ENUM di migration) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Indexing</label>
                            <select name="indexing" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                <option value="scopus" {{ old('indexing') == 'scopus' ? 'selected' : '' }}>Scopus</option>
                                <option value="wos" {{ old('indexing') == 'wos' ? 'selected' : '' }}>Web of Science (WoS)</option>
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="sinta{{ $i }}" {{ old('indexing') == "sinta$i" ? 'selected' : '' }}>SINTA {{ $i }}</option>
                                @endfor
                                <option value="non-indexed" {{ old('indexing') == 'non-indexed' ? 'selected' : '' }}>Non-Indexed</option>
                            </select>
                        </div>
                        
                        {{-- Quartile (Sesuai dengan ENUM di migration) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quartile (untuk Scopus/WoS)</label>
                            <select name="quartile" class="w-full rounded-md border-gray-300">
                                <option value="">Tidak Ada</option>
                                @foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q)
                                    <option value="{{ $q }}" {{ old('quartile') == $q ? 'selected' : '' }}>{{ $q }}</option>
                                @endforeach
                                <option value="non-quartile" {{ old('quartile') == 'non-quartile' ? 'selected' : '' }}>Non-Quartile</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Penelitian Terkait --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penelitian Terkait</label>
                        <select name="penelitian_id" class="w-full rounded-md border-gray-300">
                            <option value="">Tidak Ada</option>
                            @foreach($penelitianList as $p)
                                <option value="{{ $p->id }}" {{ old('penelitian_id') == $p->id ? 'selected' : '' }}>{{ $p->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- File Publikasi --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Publikasi (PDF, max 10MB)</label>
                        <input type="file" name="file_publikasi" accept=".pdf" 
                               class="w-full rounded-md border-gray-300">
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" 
                                  class="w-full rounded-md border-gray-300">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('publikasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #10784b;">Simpan Publikasi</button>
                    </div>
                </form>
                {{-- Akhir Form --}}

            </div>
        </div>
    </div>
</x-app-layout>