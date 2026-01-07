@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ isset($publikasi) ? __('Edit Data Publikasi') : __('Tambah Data Publikasi Baru') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <form method="POST"
                action="{{ isset($publikasi) ? route('publikasi.update', $publikasi) : route('publikasi.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if(isset($publikasi))
                    @method('PUT')
                @endif

                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Data Jurnal</h1>
                            <p class="text-sm text-gray-600 mt-1">Lengkapi informasi jurnal Anda dengan detail</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>

                    <div class="space-y-6">

                        <div>
                            <label for="judul_publikasi" class="block text-sm font-medium text-gray-700 mb-2">Judul
                                Publikasi <span class="text-red-600">*</span></label>
                            <input type="text" id="judul_publikasi" name="judul_publikasi"
                                value="{{ old('judul_publikasi', $publikasi->judul_publikasi ?? '') }}" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                placeholder="Masukkan judul jurnal">
                            @error('judul_publikasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Dynamic Authors Section --}}
                        <div x-data="{ 
                                authors: {{ isset($publikasi) && $publikasi->penulis ? json_encode(json_decode($publikasi->penulis)) : '[{ "nama": "", "nip": "", "id": ' . time() . ' }]' }},
                                addAuthor() {
                                    this.authors.push({ id: Date.now(), nama: '', nip: '' });
                                },
                                removeAuthor(index) {
                                    if (this.authors.length > 1) {
                                        this.authors.splice(index, 1);
                                    }
                                }
                             }">

                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-gray-800">Penulis (Nama Peneliti/Dosen) <span
                                        class="text-red-600">*</span></label>
                                <button type="button" @click="addAuthor()"
                                    class="text-sm text-telkom-blue hover:text-blue-700 font-medium flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Penulis
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(author, index) in authors" :key="index">
                                    <div
                                        class="flex flex-col md:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 items-end hover:border-blue-200 transition-colors">
                                        <div class="flex-1 w-full">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama
                                                Penulis</label>
                                            <input type="text" :name="'penulis[' + index + '][nama]'"
                                                x-model="author.nama"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                placeholder="Nama Lengkap Penulis" required />
                                        </div>
                                        <div class="flex-1 w-full">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">NIP</label>
                                            <input type="text" :name="'penulis[' + index + '][nip]'"
                                                x-model="author.nip"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                placeholder="NIP Penulis" />
                                        </div>
                                        <div class="w-auto">
                                            <button type="button" @click="removeAuthor(index)"
                                                :disabled="authors.length === 1"
                                                :class="authors.length === 1 
                                                    ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' 
                                                    : 'bg-white text-red-500 border-red-200 hover:bg-red-50 hover:text-red-600 hover:border-red-300 cursor-pointer shadow-sm'"
                                                class="w-[38px] h-[38px] flex items-center justify-center border rounded-md transition-all"
                                                title="Hapus Penulis">
                                                <i class="fas fa-trash-alt w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            @error('penulis')<p class="text-sm text-red-600 mt-1">Pastikan semua penulis terisi dengan
                            benar.</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span
                                        class="text-red-600">*</span></label>
                                <select id="jenis" name="jenis" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Jenis</option>
                                    <option value="jurnal" {{ old('jenis', $publikasi->jenis ?? '') == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                                    <option value="prosiding" {{ old('jenis', $publikasi->jenis ?? '') == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                                    <option value="buku" {{ old('jenis', $publikasi->jenis ?? '') == 'buku' ? 'selected' : '' }}>Buku</option>
                                </select>
                                @error('jenis')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit/Nama
                                    Jurnal <span class="text-red-600">*</span></label>
                                <input type="text" id="penerbit" name="penerbit"
                                    value="{{ old('penerbit', $publikasi->penerbit ?? '') }}" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                    placeholder="Nama penerbit atau jurnal">
                                @error('penerbit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Detail Jurnal</h2>

                    <div class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                    Terbit <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_terbit" name="tanggal_terbit"
                                    value="{{ old('tanggal_terbit', $publikasi->tanggal_terbit ?? '') }}" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_terbit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun <span
                                        class="text-red-600">*</span></label>
                                <input type="number" id="tahun" name="tahun"
                                    value="{{ old('tahun', $publikasi->tahun ?? date('Y')) }}" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                    placeholder="2025">
                                @error('tahun')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester
                                    <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil" {{ old('semester', $publikasi->semester ?? '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $publikasi->semester ?? '') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="issn_isbn"
                                    class="block text-sm font-medium text-gray-700 mb-2">ISSN/ISBN</label>
                                <input type="text" id="issn_isbn" name="issn_isbn"
                                    value="{{ old('issn_isbn', $publikasi->issn_isbn ?? '') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                    placeholder="1234-5678">
                                @error('issn_isbn')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="doi" class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                                <input type="text" id="doi" name="doi" value="{{ old('doi', $publikasi->doi ?? '') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                    placeholder="10.xxxx/xxxxx">
                                @error('doi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL
                                    Publikasi</label>
                                <input type="url" id="url" name="url" placeholder="https://"
                                    value="{{ old('url', $publikasi->url ?? 'https://') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                    placeholder="https://">
                                @error('url')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Indexing & Kualitas
                    </h2>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label for="indexing"
                                    class="block text-sm font-medium text-gray-700 mb-2">Indexing</label>
                                <select id="indexing" name="indexing"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Tidak Ada</option>
                                    <option value="sinta1" {{ old('indexing', $publikasi->indexing ?? '') == 'sinta1' ? 'selected' : '' }}>SINTA 1</option>
                                    <option value="sinta2" {{ old('indexing', $publikasi->indexing ?? '') == 'sinta2' ? 'selected' : '' }}>SINTA 2</option>
                                    <option value="scopus" {{ old('indexing', $publikasi->indexing ?? '') == 'scopus' ? 'selected' : '' }}>Scopus</option>
                                    <option value="wos" {{ old('indexing', $publikasi->indexing ?? '') == 'wos' ? 'selected' : '' }}>Web of Science</option>
                                </select>
                                @error('indexing')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="quartile" class="block text-sm font-medium text-gray-700 mb-2">Quartile
                                    (untuk Scopus/WoS)</label>
                                <select id="quartile" name="quartile"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Tidak Ada</option>
                                    <option value="Q1" {{ old('quartile', $publikasi->quartile ?? '') == 'Q1' ? 'selected' : '' }}>Q1</option>
                                    <option value="Q2" {{ old('quartile', $publikasi->quartile ?? '') == 'Q2' ? 'selected' : '' }}>Q2</option>
                                    <option value="Q3" {{ old('quartile', $publikasi->quartile ?? '') == 'Q3' ? 'selected' : '' }}>Q3</option>
                                    <option value="Q4" {{ old('quartile', $publikasi->quartile ?? '') == 'Q4' ? 'selected' : '' }}>Q4</option>
                                </select>
                                @error('quartile')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="penelitian_terkait"
                                class="block text-sm font-medium text-gray-700 mb-2">Penelitian Terkait</label>
                            <select id="penelitian_terkait" name="penelitian_terkait"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                <option value="">Tidak Ada</option>
                                <option value="penelitian1">Penelitian 1</option>
                                <option value="penelitian2">Penelitian 2</option>
                                <option value="penelitian3">Penelitian 3</option>
                            </select>
                            @error('penelitian_terkait')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- File & Catatan Section --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">File & Catatan</h2>

                    <div class="space-y-6">

                        {{-- File Jurnal Upload --}}
                        <div class="file-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Jurnal (PDF, max
                                10MB)</label>

                            @if(isset($publikasi) && $publikasi->file_path)
                                {{-- Tampilan jika file sudah ada --}}
                                <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card"
                                    id="file_publikasi_card">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit(basename($publikasi->file_path), 30, '...') }}
                                                </p>
                                                <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($publikasi->file_path) }}" target="_blank"
                                            class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti
                                    dokumen.</p>

                                {{-- Upload area untuk mengganti file --}}
                                <div class="file-upload-area" id="file_publikasi_replace">
                                    <label for="file_publikasi"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span>
                                                atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                        </div>
                                        <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf"
                                            class="hidden">
                                    </label>
                                </div>
                            @else
                                {{-- Upload area kosong --}}
                                <div class="file-upload-area" id="file_publikasi_upload">
                                    <label for="file_publikasi"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag
                                                and drop
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                        </div>
                                        <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf"
                                            class="hidden">
                                    </label>
                                </div>
                            @endif
                            @error('file_publikasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                (Opsional)</label>
                            <textarea id="catatan" name="catatan" rows="4"
                                placeholder="Tambahkan catatan atau informasi tambahan..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none">{{ old('catatan', $publikasi->catatan ?? '') }}</textarea>
                            @error('catatan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('publikasi.index') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-sm font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle selected file and render a file-card similar to 'penelitian' and 'pengmas' create
        function handleFileChange(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const existingCard = document.getElementById(cardId);

            if (!input) return;
            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (uploadArea) uploadArea.style.display = 'none';

                    let card = existingCard;
                    if (!card) {
                        card = document.createElement('div');
                        card.id = cardId;
                        card.className = 'p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card';
                        uploadArea.parentNode.insertBefore(card, uploadArea);
                    }

                    const maxLength = 30;
                    const displayName = file.name.length > maxLength ? file.name.substring(0, maxLength) + '...' : file.name;

                    card.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0 mr-3">
                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">${displayName}</p>
                                    <p class="text-xs text-gray-500">File dipilih</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile('${inputId}', '${uploadAreaId}', '${cardId}')" class="text-red-500 hover:text-red-700 text-sm flex-shrink-0">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </div>
                    `;
                }
            });
        }

        // Remove selected file and show upload area again
        function removeFile(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const card = document.getElementById(cardId);

            if (input) input.value = '';
            if (card) card.style.display = 'none';
            if (uploadArea) uploadArea.style.display = 'block';
        }

        // Enable drag & drop for label-based upload areas
        function setupDragAndDrop(inputId, uploadAreaId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            if (!input || !uploadArea) return;

            const zone = uploadArea.querySelector('label') || uploadArea;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                zone.addEventListener(eventName, () => zone.classList.add('border-blue-500', 'bg-blue-50'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, () => zone.classList.remove('border-blue-500', 'bg-blue-50'), false);
            });

            zone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length) {
                    input.files = files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }, false);
        }

        // Initialize for all file inputs/areas
        document.addEventListener('DOMContentLoaded', function () {
            handleFileChange('file_publikasi', 'file_publikasi_upload', 'file_publikasi_card');
            handleFileChange('file_publikasi', 'file_publikasi_replace', 'file_publikasi_card');

            setupDragAndDrop('file_publikasi', 'file_publikasi_upload');
            setupDragAndDrop('file_publikasi', 'file_publikasi_replace');
        });
    </script>
</x-app-layout>