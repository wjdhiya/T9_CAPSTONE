<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ __('Edit Data Publikasi') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('publikasi.update', $publikasi) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Edit Data Publikasi</h1>
                            <p class="text-sm text-gray-600 mt-1">Ubah informasi publikasi Anda dengan detail</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi <span class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $publikasi->judul) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                   placeholder="Masukkan judul publikasi">
                            @error('judul')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div x-data="{ 
                                authors: {{ json_encode(old('author', isset($publikasi) && $publikasi->penulis ? [['first' => $publikasi->penulis_depan_0 ?? '', 'last' => $publikasi->penulis_belakang_0 ?? '']] : [['first' => '', 'last' => '']])) }},
                                nextId: 1, 
                                addAuthor() {
                                    this.authors.push({ id: Date.now(), first: '', last: '' });
                                    this.nextId++;
                                },
                                removeAuthor(id) {
                                    if (this.authors.length > 1) {
                                        this.authors = this.authors.filter(author => author.id !== id);
                                    }
                                }
                             }">

                            <label class="block text-sm font-medium text-gray-700 mb-2">Penulis (Nama Peneliti/Dosen) <span class="text-red-600">*</span></label>
                            
                            <div class="space-y-2">
                                <template x-for="(author, index) in authors" :key="author.id">
                                    <div class="flex items-center gap-3">
                                        
                                        {{-- Nama Depan --}}
                                        <input
                                            type="text"
                                            :name="'author[' + index + '][first]'"
                                            x-model="author.first"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all outline-none"
                                            placeholder="Nama Depan"
                                            required
                                        />
                                        {{-- Nama Belakang --}}
                                        <input
                                            type="text"
                                            :name="'author[' + index + '][last]'"
                                            x-model="author.last"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all outline-none"
                                            placeholder="Nama Belakang"
                                        />
                                        {{-- Tombol Hapus --}}
                                        <button
                                            type="button"
                                            @click="removeAuthor(author.id)"
                                            :disabled="authors.length === 1"
                                            class="p-2.5 border border-gray-200 rounded-lg hover:bg-red-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Hapus Penulis"
                                        >
                                            <i class="fas fa-trash-alt w-5 h-5" :class="authors.length === 1 ? 'text-gray-300' : 'text-red-500'"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            {{-- Tombol Tambah Penulis --}}
                            <button
                                type="button"
                                @click="addAuthor()"
                                class="flex items-center gap-2 px-4 py-2.5 mt-3 text-sm bg-blue-50 text-telkom-blue rounded-lg hover:bg-blue-100 transition-colors font-medium border border-transparent"
                            >
                                <i class="fas fa-plus w-4 h-4"></i>
                                Tambah Penulis
                            </button>
                            
                            @error('author')<p class="text-sm text-red-600 mt-1">Pastikan semua penulis terisi dengan benar.</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-600">*</span></label>
                                <select id="jenis" name="jenis" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Jenis</option>
                                    <option value="jurnal" {{ old('jenis', $publikasi->jenis) == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                                    <option value="prosiding" {{ old('jenis', $publikasi->jenis) == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                                    <option value="buku" {{ old('jenis', $publikasi->jenis) == 'buku' ? 'selected' : '' }}>Buku</option>
                                </select>
                                @error('jenis')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit/Nama Jurnal <span class="text-red-600">*</span></label>
                                <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $publikasi->penerbit) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Nama penerbit atau jurnal">
                                @error('penerbit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Detail Publikasi --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Detail Publikasi</h2>
                    
                    <div class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_terbit" name="tanggal_terbit" value="{{ old('tanggal_terbit', $publikasi->tanggal_terbit?->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_terbit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik <span class="text-red-600">*</span></label>
                                <input type="number" id="tahun_akademik" name="tahun_akademik" value="{{ old('tahun_akademik', $publikasi->tahun_akademik) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="2025">
                                @error('tahun_akademik')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil" {{ old('semester', $publikasi->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $publikasi->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="issn_isbn" class="block text-sm font-medium text-gray-700 mb-2">ISSN/ISBN</label>
                                <input type="text" id="issn_isbn" name="issn_isbn" value="{{ old('issn_isbn', $publikasi->issn_isbn) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="1234-5678">
                                @error('issn_isbn')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="doi" class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                                <input type="text" id="doi" name="doi" value="{{ old('doi', $publikasi->doi) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="10.xxxx/xxxxx">
                                @error('doi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL Publikasi</label>
                                <input type="url" id="url" name="url" placeholder="https://" value="{{ old('url', $publikasi->url ?? 'https://') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="https://">
                                @error('url')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Indexing & Kualitas --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Indexing & Kualitas</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div>
                                <label for="indexing" class="block text-sm font-medium text-gray-700 mb-2">Indexing</label>
                                <select id="indexing" name="indexing" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Tidak Ada</option>
                                    <option value="sinta1" {{ old('indexing', $publikasi->indexing) == 'sinta1' ? 'selected' : '' }}>SINTA 1</option>
                                    <option value="sinta2" {{ old('indexing', $publikasi->indexing) == 'sinta2' ? 'selected' : '' }}>SINTA 2</option>
                                    <option value="scopus" {{ old('indexing', $publikasi->indexing) == 'scopus' ? 'selected' : '' }}>Scopus</option>
                                    <option value="wos" {{ old('indexing', $publikasi->indexing) == 'wos' ? 'selected' : '' }}>Web of Science</option>
                                </select>
                                @error('indexing')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="quartile" class="block text-sm font-medium text-gray-700 mb-2">Quartile (untuk Scopus/WoS)</label>
                                <select id="quartile" name="quartile" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Tidak Ada</option>
                                    <option value="Q1" {{ old('quartile', $publikasi->quartile) == 'Q1' ? 'selected' : '' }}>Q1</option>
                                    <option value="Q2" {{ old('quartile', $publikasi->quartile) == 'Q2' ? 'selected' : '' }}>Q2</option>
                                    <option value="Q3" {{ old('quartile', $publikasi->quartile) == 'Q3' ? 'selected' : '' }}>Q3</option>
                                    <option value="Q4" {{ old('quartile', $publikasi->quartile) == 'Q4' ? 'selected' : '' }}>Q4</option>
                                </select>
                                @error('quartile')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: File & Catatan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">File & Catatan</h2>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label for="file_publikasi" class="block text-sm font-medium text-gray-700 mb-2">File Publikasi (PDF, max 10MB)</label>
                            
                            @if($publikasi->file_path)
                                {{-- Tampilan jika file sudah ada --}}
                                <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg">
                                    <p class="text-sm text-gray-900 font-medium">Dokumen Tersimpan: <a href="{{ Storage::url($publikasi->file_path) }}" target="_blank" class="text-telkom-blue underline">Lihat File</a></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf"
                                   class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                            @else
                                {{-- Tampilan Upload Kosong (Gaya Dropzone) --}}
                                <label for="file_publikasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-telkom-blue border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-blue-50 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
                                        <p class="text-sm text-gray-600">
                                            <span class="text-telkom-blue font-semibold">Klik untuk upload</span> atau drag and drop
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                    </div>
                                    <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf" class="hidden">
                                </label>
                            @endif
                            @error('file_publikasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="catatan" name="catatan" rows="4" placeholder="Tambahkan catatan atau informasi tambahan..."
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none">{{ old('catatan', $publikasi->catatan) }}</textarea>
                            @error('catatan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('publikasi.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-sm font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-telkom-green text-white rounded-lg hover:bg-telkom-green hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Update Publikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
