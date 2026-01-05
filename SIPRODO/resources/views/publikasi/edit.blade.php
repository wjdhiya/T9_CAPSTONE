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
                                <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_publikasi_card">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-900 font-medium">Dokumen Tersimpan: <a href="{{ Storage::url($publikasi->file_path) }}" target="_blank" class="text-telkom-blue underline">Lihat File</a></p>
                                            <p class="text-xs text-gray-500 mt-1">Dokumen tersimpan</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ Storage::url($publikasi->file_path) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                            <button type="button" onclick="removeFile('file_publikasi', 'file_publikasi_replace', 'file_publikasi_card')" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1" title="Hapus File">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                {{-- Tampilan Upload Ganti File (Gaya Dropzone) --}}
                                <div class="file-upload-area" id="file_publikasi_replace">
                                    <label for="file_publikasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="text-telkom-blue font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                        </div>
                                        <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf" class="hidden" data-preview-id="preview_publikasi_card">
                                    </label>
                                </div>
                            @else
                                {{-- Tampilan Upload Kosong (Gaya Dropzone) --}}
                                <div class="file-upload-area" id="file_publikasi_upload">
                                    <label for="file_publikasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                        </div>
                                        <input type="file" id="file_publikasi" name="file_publikasi" accept=".pdf" class="hidden" data-preview-id="preview_publikasi_card">
                                    </label>
                                </div>
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
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Update Publikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Helper: cari upload area yang relevan (replace atau upload)
        function findUploadArea(input, uploadAreaId) {
            if (!input) return document.getElementById(uploadAreaId) || document.getElementById(uploadAreaId + '_upload') || document.getElementById(uploadAreaId + '_replace') || document.querySelector('.file-upload-area');
            let uploadArea = document.getElementById(uploadAreaId);
            if (!uploadArea) {
                // coba naik ke parent untuk menemukan .file-upload-area
                uploadArea = input.closest('.file-upload-area') || input.closest('label')?.closest('.file-upload-area');
            }
            if (!uploadArea) {
                // fallback: cari area pertama di sekitar input
                uploadArea = input.closest('div')?.querySelector('.file-upload-area') || document.querySelector('.file-upload-area');
            }
            return uploadArea;
        }

        // Function to handle file input change and show preview
        function handleFileChange(inputId, uploadAreaId, previewId) {
            let input = document.getElementById(inputId);
            if (!input) return;

            // Prevent attaching multiple handlers to the same input
            if (input.dataset.fileHandlerAttached) return;
            input.dataset.fileHandlerAttached = '1';

            input.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const uploadArea = findUploadArea(input, uploadAreaId);
                const storedCard = document.getElementById(inputId + '_card'); // e.g., file_publikasi_card

                // Hide upload area if found (use inline style to keep control simple)
                if (uploadArea) uploadArea.style.display = 'none';
                // Hide stored card (if any) to avoid duplicate UI
                if (storedCard) storedCard.style.display = 'none';

                // Remove existing hidden remove flag (user replaced instead of deleting)
                const existingFlag = document.querySelector(`input[name="remove_${inputId}"]`);
                if (existingFlag) existingFlag.remove();

                // Create preview card
                let preview = document.getElementById(previewId);
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = previewId;
                    preview.className = 'p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card mb-2';
                    if (uploadArea && uploadArea.parentNode) {
                        uploadArea.parentNode.insertBefore(preview, uploadArea);
                    } else {
                        input.closest('div')?.insertBefore(preview, input);
                    }
                } else {
                    preview.style.display = 'block';
                }

                const maxLength = 40;
                const displayName = file.name.length > maxLength ? file.name.substring(0, maxLength) + '...' : file.name;

                preview.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3 flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${displayName}</p>
                                <p class="text-xs text-gray-500">File dipilih (Belum disimpan)</p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile('${inputId}', '${uploadAreaId}', '${previewId}')" class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                `;
            });
        }

        // Remove selected/preview file or stored file
        function removeFile(inputId, uploadAreaId, cardId) {
            let input = document.getElementById(inputId);
            // Try to find the correct upload area dynamically
            let uploadArea = document.getElementById(uploadAreaId) || (input ? findUploadArea(input, uploadAreaId) : null);
            const card = document.getElementById(cardId);
            const storedCard = document.getElementById(inputId + '_card');

            // If removing stored card (user clicked Hapus on saved file)
            if (cardId === inputId + '_card') {
                if (card) card.style.display = 'none';
                if (uploadArea) uploadArea.style.display = ''; // remove inline display to let CSS show it
                // add hidden flag so backend knows to delete the stored file
                let flag = document.querySelector(`input[name="remove_${inputId}"]`);
                if (!flag) {
                    flag = document.createElement('input');
                    flag.type = 'hidden';
                    flag.name = `remove_${inputId}`;
                    flag.value = '1';
                    const form = document.querySelector('form');
                    if (form) form.appendChild(flag);
                } else {
                    flag.value = '1';
                }
                return;
            }

            // If removing preview/selected file (cancel)
            if (input) {
                try {
                    input.value = '';
                } catch (err) {
                    // Fallback: replace with cloned input (preserve id/name/data-preview-id)
                    const newInput = input.cloneNode();
                    newInput.id = input.id;
                    newInput.name = input.name;
                    if (input.dataset && input.dataset.previewId) newInput.dataset.previewId = input.dataset.previewId;
                    input.parentNode.replaceChild(newInput, input);
                    input = newInput;
                    // Re-init handlers for the new input so upload still works after cancel
                    const previewId = newInput.dataset.previewId || ('preview_' + newInput.id + '_card');
                    handleFileChange(newInput.id, uploadAreaId, previewId);
                    setupDragAndDrop(newInput.id, uploadAreaId);
                }
            }

            // Remove preview card from DOM (if exists)
            if (card) {
                try {
                    card.remove();
                } catch (e) {
                    card.style.display = 'none';
                }
            }

            // Always show the upload area after cancel so user can re-upload
            if (!uploadArea && input) uploadArea = findUploadArea(input, uploadAreaId);
            if (uploadArea) uploadArea.style.display = '';

            // If a stored card exists, restore it
            if (storedCard) {
                storedCard.style.display = '';
            }

            // remove any remove_* flag
            const existingFlag = document.querySelector(`input[name="remove_${inputId}"]`);
            if (existingFlag) existingFlag.remove();
        }

        // Setup drag & drop on upload areas
        function setupDragAndDrop(inputId, uploadAreaId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            if (!input || !uploadArea) return;

            const zone = uploadArea.querySelector('label') || uploadArea;

            ['dragenter','dragover','dragleave','drop'].forEach(evt => {
                zone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); }, false);
            });

            ['dragenter','dragover'].forEach(evt => {
                zone.addEventListener(evt, () => zone.classList.add('border-blue-500','bg-blue-50'), false);
            });

            ['dragleave','drop'].forEach(evt => {
                zone.addEventListener(evt, () => zone.classList.remove('border-blue-500','bg-blue-50'), false);
            });

            zone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length) {
                    const dataTransfer = new DataTransfer();
                    for (let i = 0; i < files.length; i++) dataTransfer.items.add(files[i]);
                    input.files = dataTransfer.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }, false);
        }

        // Initialize handlers for publikasi file
        document.addEventListener('DOMContentLoaded', function() {
            // single handler will adapt to whichever upload area exists
            handleFileChange('file_publikasi', 'file_publikasi_replace', 'preview_publikasi_card');
            // also attempt to wire drag & drop for both possible areas (will noop if not present)
            setupDragAndDrop('file_publikasi', 'file_publikasi_upload');
            setupDragAndDrop('file_publikasi', 'file_publikasi_replace');
        });
    </script>

</x-app-layout>
