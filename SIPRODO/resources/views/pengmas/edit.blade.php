@php
    use Illuminate\Support\Str;

    // Helper yang lebih kuat untuk decode data (JSON, Array, atau String)
    $getArrayData = function($field, $data) {
        // 1. Cek data dari old input (validasi gagal)
        $old = old($field);
        if ($old && is_array($old)) return $old;

        // 2. Cek data dari database
        if (!empty($data)) {
            // Jika sudah array (karena $casts di model), kembalikan langsung
            if (is_array($data)) return $data;

            // Jika string JSON
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            // Jika string biasa dipisahkan koma
            if (is_string($data) && str_contains($data, ',')) {
                return array_map('trim', explode(',', $data));
            }

            // Jika string tunggal
            return [$data];
        }

        // 3. Default kosong
        return [''];
    };

    // Persiapan data Dosen
    $oldAnggota = $getArrayData('anggota', $pengabdianMasyarakat->anggota);
    if (empty($oldAnggota)) $oldAnggota = [''];

    // Persiapan data Mahasiswa
    $dataMahasiswaDB = $pengabdianMasyarakat->mahasiswa ?? $pengabdianMasyarakat->mahasiswa_terlibat;
    $oldMahasiswa = $getArrayData('mahasiswa', $dataMahasiswaDB);
    if (empty($oldMahasiswa)) $oldMahasiswa = [''];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ __('Edit Data Pengabdian Masyarakat') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('pengmas.update', $pengabdianMasyarakat) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Edit Data</h1>
                            <p class="text-sm text-gray-600 mt-1">Ubah informasi pengabdian masyarakat</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Kegiatan <span class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $pengabdianMasyarakat->judul) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                            @error('judul')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="abstrak" class="block text-sm font-medium text-gray-700 mb-2">Abstrak / Deskripsi <span class="text-red-600">*</span></label>
                            <textarea id="abstrak" name="abstrak" rows="4" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none">{{ old('abstrak', $pengabdianMasyarakat->abstrak) }}</textarea>
                            @error('abstrak')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kegiatan <span class="text-red-600">*</span></label>
                                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $pengabdianMasyarakat->lokasi) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('lokasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="mitra" class="block text-sm font-medium text-gray-700 mb-2">Mitra Sasaran <span class="text-red-600">*</span></label>
                                <input type="text" id="mitra" name="mitra" value="{{ old('mitra', $pengabdianMasyarakat->mitra) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('mitra')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Waktu & Pelaksanaan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Waktu & Pelaksanaan</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik <span class="text-red-600">*</span></label>
                                <input type="text" id="tahun_akademik" name="tahun_akademik" value="{{ old('tahun_akademik', $pengabdianMasyarakat->tahun_akademik) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tahun_akademik')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="ganjil" {{ old('semester', $pengabdianMasyarakat->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $pengabdianMasyarakat->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">Jml. Peserta <span class="text-red-600">*</span></label>
                                <input type="number" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', $pengabdianMasyarakat->jumlah_peserta) }}" min="1" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('jumlah_peserta')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengabdianMasyarakat->tanggal_mulai?->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_mulai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengabdianMasyarakat->tanggal_selesai?->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_selesai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pendanaan & Tim --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Pendanaan & Tim</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="dana" class="block text-sm font-medium text-gray-700 mb-2">Nominal Dana (Rp)</label>
                                <input type="number" id="dana" name="dana" value="{{ old('dana', $pengabdianMasyarakat->dana) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="0">
                                @error('dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                                <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $pengabdianMasyarakat->sumber_dana) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Mandiri / Hibah / Kampus">
                                @error('sumber_dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Input Dinamis untuk Anggota Dosen & Mahasiswa --}}
                        <div x-data="{ 
                            dosenItems: {{ json_encode($oldAnggota) }},
                            mahasiswaItems: {{ json_encode($oldMahasiswa) }},
                            addDosen() { this.dosenItems.push(''); },
                            removeDosen(index) { if(this.dosenItems.length > 1) this.dosenItems.splice(index, 1); },
                            addMahasiswa() { this.mahasiswaItems.push(''); },
                            removeMahasiswa(index) { if(this.mahasiswaItems.length > 1) this.mahasiswaItems.splice(index, 1); }
                        }">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tim Pelaksana</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Kolom Dosen --}}
                                <div class="">
                                    <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-chalkboard-teacher mr-1"></i> Nama Dosen</label>
                                    <div class="space-y-3">
                                        <template x-for="(dosen, index) in dosenItems" :key="'dosen-'+index">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="anggota[]" x-model="dosenItems[index]"
                                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 bg-white"
                                                       placeholder="Nama Dosen">
                                                
                                                <button type="button" 
                                                        @click="removeDosen(index)" 
                                                        :disabled="dosenItems.length === 1"
                                                        class="p-2.5 rounded-lg transition-colors border border-gray-200" 
                                                        :class="dosenItems.length === 1 ? 'text-gray-300 cursor-not-allowed bg-gray-50' : 'text-red-500 hover:bg-red-50 hover:border-red-200 cursor-pointer'"
                                                        title="Hapus Dosen" aria-label="Hapus Dosen">
                                                    <!-- Inline SVG ikon tong sampah (tidak bergantung pada FontAwesome) -->
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addDosen()" class="w-full mt-3 py-2 text-sm font-medium text-black-600 bg-white border border-white-300 rounded-lg hover:bg-black-50 transition-colors flex justify-center items-center">
                                        <i class="fas fa-plus mr-2"></i> Tambah Dosen
                                    </button>
                                </div>

                                {{-- Kolom Mahasiswa --}}
                                <div class="">
                                    <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-user-graduate mr-1"></i> Nama Mahasiswa</label>
                                    <div class="space-y-3">
                                        <template x-for="(mhs, index) in mahasiswaItems" :key="'mhs-'+index">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="mahasiswa[]" x-model="mahasiswaItems[index]"
                                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 bg-white"
                                                       placeholder="Nama Mahasiswa">
                                                
                                                <!-- Kotak kecil dengan ikon tong sampah (sama seperti Dosen) -->
                                                <button type="button"
                                                        @click="removeMahasiswa(index)"
                                                        :disabled="mahasiswaItems.length === 1"
                                                        class="p-2.5 rounded-lg transition-colors border border-gray-200"
                                                        :class="mahasiswaItems.length === 1 ? 'text-gray-300 cursor-not-allowed bg-gray-50' : 'text-red-500 hover:bg-red-50 hover:border-red-200 cursor-pointer'"
                                                        title="Hapus Mahasiswa" aria-label="Hapus Mahasiswa">
                                                    <!-- Inline SVG ikon tong sampah -->
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addMahasiswa()" class="w-full mt-3 py-2 text-sm font-medium text-black-600 bg-white border border-white-300 rounded-lg hover:bg-black-50 transition-colors flex justify-center items-center">
                                        <i class="fas fa-plus mr-2"></i> Tambah Mahasiswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Dokumen & Status --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Dokumen & Status</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Kegiatan <span class="text-red-600">*</span></label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                <option value="proposal" {{ old('status', $pengabdianMasyarakat->status) == 'proposal' ? 'selected' : '' }}>📋 Proposal (Baru Diajukan)</option>
                                <option value="berjalan" {{ old('status', $pengabdianMasyarakat->status) == 'berjalan' ? 'selected' : '' }}>🏃 Sedang Berjalan</option>
                                <option value="selesai" {{ old('status', $pengabdianMasyarakat->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="ditolak" {{ old('status', $pengabdianMasyarakat->status) == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                            @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Upload Files --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            
                            {{-- File Proposal --}}
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                
                                @if($pengabdianMasyarakat->file_proposal)
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_proposal_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center overflow-hidden">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3 flex-shrink-0"></i>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ basename($pengabdianMasyarakat->file_proposal) }}">
                                                        {{ Str::limit(basename($pengabdianMasyarakat->file_proposal), 20, '...') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_proposal) }}" target="_blank" class="text-telkom-blue hover:underline text-sm ml-2 flex-shrink-0">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>

                                    <div class="file-upload-area" id="file_proposal_replace">
                                        <label for="file_proposal" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_proposal" name="file_proposal" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    <div class="file-upload-area" id="file_proposal_upload">
                                        <label for="file_proposal" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_proposal" name="file_proposal" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_proposal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- File Laporan --}}
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF, max 10MB)</label>
                                
                                @if($pengabdianMasyarakat->file_laporan)
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_laporan_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center overflow-hidden">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3 flex-shrink-0"></i>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ basename($pengabdianMasyarakat->file_laporan) }}">
                                                        {{ Str::limit(basename($pengabdianMasyarakat->file_laporan), 20, '...') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_laporan) }}" target="_blank" class="text-telkom-blue hover:underline text-sm ml-2 flex-shrink-0">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>

                                    <div class="file-upload-area" id="file_laporan_replace">
                                        <label for="file_laporan" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_laporan" name="file_laporan" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    <div class="file-upload-area" id="file_laporan_upload">
                                        <label for="file_laporan" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_laporan" name="file_laporan" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_laporan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- File Dokumentasi --}}
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (Foto/ZIP, max 10MB)</label>
                                
                                @if($pengabdianMasyarakat->file_dokumentasi)
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_dokumentasi_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center overflow-hidden">
                                                <i class="fas fa-file-archive w-6 h-6 text-yellow-600 mr-3 flex-shrink-0"></i>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ basename($pengabdianMasyarakat->file_dokumentasi) }}">
                                                        {{ Str::limit(basename($pengabdianMasyarakat->file_dokumentasi), 20, '...') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_dokumentasi) }}" target="_blank" class="text-telkom-blue hover:underline text-sm ml-2 flex-shrink-0">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>

                                    <div class="file-upload-area" id="file_dokumentasi_replace">
                                        <label for="file_dokumentasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">JPG/PNG/ZIP (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_dokumentasi" name="file_dokumentasi" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    <div class="file-upload-area" id="file_dokumentasi_upload">
                                        <label for="file_dokumentasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">JPG/PNG/ZIP (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_dokumentasi" name="file_dokumentasi" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_dokumentasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('pengmas.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-sm font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Update Pengmas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle selected file and render a file-card similar to 'create' page logic
        function handleFileChange(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            // Element card ini mungkin sudah ada (jika file existing) atau belum
            // Logic ini akan menghandle pembuatan/update card preview
            
            if (!input) return;

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const existingCard = document.getElementById(cardId);
                
                if (file) {
                    if (uploadArea) uploadArea.style.display = 'none';

                    let card = existingCard;
                    
                    // Jika card belum ada (kasus upload baru tanpa file existing), buat baru
                    if (!card) {
                        card = document.createElement('div');
                        card.id = cardId;
                        // tandai sebagai preview agar deteksi lebih aman
                        card.dataset.preview = 'true';
                        card.className = 'p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card mb-2';
                        // Insert card sebelum upload area
                        if(uploadArea && uploadArea.parentNode) {
                            uploadArea.parentNode.insertBefore(card, uploadArea);
                        }
                    } else {
                        // Jika card sudah ada (misal replace file existing), pastikan terlihat
                        card.style.display = 'block';
                        // jika card sudah ada karena preview sebelumnya, pastikan ditandai
                        if (!card.dataset.preview) card.dataset.preview = 'true';
                    }

                    const maxLength = 30;
                    const displayName = file.name.length > maxLength ? file.name.substring(0, maxLength) + '...' : file.name;
                    
                    // Tentukan icon berdasarkan input ID (proposal/laporan=pdf, dokumentasi=archive)
                    let iconClass = 'fas fa-file-pdf text-red-500'; // Default PDF
                    if (inputId === 'file_dokumentasi') {
                        iconClass = 'fas fa-file-archive text-yellow-600';
                    }

                    card.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0 mr-3">
                                <i class="${iconClass} w-6 h-6 mr-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">${displayName}</p>
                                    <p class="text-xs text-gray-500">File dipilih (Belum disimpan)</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile('${inputId}', '${uploadAreaId}', '${cardId}')" class="text-red-500 hover:text-red-700 text-sm flex-shrink-0">
                                <i class="fas fa-times"></i> Batal
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

            if (input) input.value = ''; // Reset input file

            // Usahakan menampilkan kembali semua area upload (card putus-putus) di container terkait
            // sehingga "Batal" tidak membuat card putus-putus ikut hilang.
            const container = (card && card.parentNode) || (uploadArea && uploadArea.parentNode);
            if (container) {
                const uploadAreas = container.querySelectorAll('.file-upload-area');
                uploadAreas.forEach(a => a.style.display = 'block');
            }
            if (uploadArea) uploadArea.style.display = 'block';

            if (!card) return;

            // Hanya hapus card preview sementara (yang berisi "File dipilih" atau yang ditandai data-preview)
            const isPreview = card.dataset.preview === 'true' || card.innerHTML.includes('File dipilih');
            if (isPreview) {
                card.remove();
                return;
            }

            // Jika card ini adalah card existing dari server (dokumen tersimpan), sembunyikan card tersebut
            // tapi JANGAN hapus area upload (agar card putus-putus tetap ada untuk replace).
            card.style.display = 'none';
        }

        // Enable drag & drop for label-based upload areas
        function setupDragAndDrop(inputId, uploadAreaId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            if (!input || !uploadArea) return;

            // Target label inside the div usually
            const zone = uploadArea.querySelector('label') || uploadArea;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, function(e) {
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
        document.addEventListener('DOMContentLoaded', function() {
            // Kita gunakan ID unik untuk card preview agar tidak menimpa card file existing
            // File Proposal
            handleFileChange('file_proposal', 'file_proposal_upload', 'preview_proposal_card'); 
            handleFileChange('file_proposal', 'file_proposal_replace', 'preview_proposal_card');
            
            // File Laporan
            handleFileChange('file_laporan', 'file_laporan_upload', 'preview_laporan_card');
            handleFileChange('file_laporan', 'file_laporan_replace', 'preview_laporan_card');
            
            // File Dokumentasi
            handleFileChange('file_dokumentasi', 'file_dokumentasi_upload', 'preview_dokumentasi_card');
            handleFileChange('file_dokumentasi', 'file_dokumentasi_replace', 'preview_dokumentasi_card');

            setupDragAndDrop('file_proposal', 'file_proposal_upload');
            setupDragAndDrop('file_proposal', 'file_proposal_replace');
            setupDragAndDrop('file_laporan', 'file_laporan_upload');
            setupDragAndDrop('file_laporan', 'file_laporan_replace');
            setupDragAndDrop('file_dokumentasi', 'file_dokumentasi_upload');
            setupDragAndDrop('file_dokumentasi', 'file_dokumentasi_replace');
        });
    </script>
</x-app-layout>