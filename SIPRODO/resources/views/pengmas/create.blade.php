@php
    use Illuminate\Support\Str;

    // --- LOGIKA PERSIAPAN DATA DOSEN (NAMA & NIP) ---
    $dosenList = [];
    // 1. Cek Old Input (validasi error)
    if (old('anggota')) {
        $oldNames = old('anggota', []);
        $oldNips = old('dosen_nip', []);
        foreach($oldNames as $index => $name) {
            $dosenList[] = [
                'nama' => $name,
                'nip' => $oldNips[$index] ?? ''
            ];
        }
    } 
    // 2. Cek Data Database (Edit Mode)
    elseif (isset($pengabdianMasyarakat) && $pengabdianMasyarakat->anggota) {
        $decoded = json_decode($pengabdianMasyarakat->anggota, true);
        if (is_array($decoded)) {
            foreach($decoded as $item) {
                // Handle jika format lama (string) atau baru (object)
                if (is_string($item)) {
                    $dosenList[] = ['nama' => $item, 'nip' => ''];
                } else {
                    $dosenList[] = ['nama' => $item['nama'] ?? '', 'nip' => $item['nip'] ?? ''];
                }
            }
        }
    }
    // 3. Default Kosong
    if (empty($dosenList)) $dosenList[] = ['nama' => '', 'nip' => ''];


    // --- LOGIKA PERSIAPAN DATA MAHASISWA (NAMA & NIM) ---
    $mahasiswaList = [];
    // 1. Cek Old Input
    if (old('mahasiswa')) {
        $oldMhsNames = old('mahasiswa', []);
        $oldMhsNims = old('mahasiswa_nim', []);
        foreach($oldMhsNames as $index => $name) {
            $mahasiswaList[] = [
                'nama' => $name,
                'nim' => $oldMhsNims[$index] ?? ''
            ];
        }
    }
    // 2. Cek Data Database
    elseif (isset($pengabdianMasyarakat) && $pengabdianMasyarakat->mahasiswa) {
        $decodedMhs = json_decode($pengabdianMasyarakat->mahasiswa, true);
        if (is_array($decodedMhs)) {
            foreach($decodedMhs as $item) {
                if (is_string($item)) {
                    $mahasiswaList[] = ['nama' => $item, 'nim' => ''];
                } else {
                    $mahasiswaList[] = ['nama' => $item['nama'] ?? '', 'nim' => $item['nim'] ?? ''];
                }
            }
        }
    }
    // 3. Default Kosong
    if (empty($mahasiswaList)) $mahasiswaList[] = ['nama' => '', 'nim' => ''];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ isset($pengabdianMasyarakat) ? __('Edit Data Pengabdian Masyarakat') : __('Tambah Data Pengabdian Masyarakat Baru') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ isset($pengabdianMasyarakat) ? route('pengmas.update', $pengabdianMasyarakat) : route('pengmas.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($pengabdianMasyarakat))
                    @method('PUT')
                @endif
                
                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Data Pengabdian Masyarakat</h1>
                            <p class="text-sm text-gray-600 mt-1">Lengkapi informasi pengabdian masyarakat Anda dengan detail</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Kegiatan <span class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $pengabdianMasyarakat->judul ?? '') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                   placeholder="Masukkan judul kegiatan pengabdian">
                            @error('judul')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="abstrak" class="block text-sm font-medium text-gray-700 mb-2">Abstrak / Deskripsi <span class="text-red-600">*</span></label>
                            <textarea id="abstrak" name="abstrak" rows="4" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none"
                                      placeholder="Jelaskan secara singkat mengenai kegiatan ini...">{{ old('abstrak', $pengabdianMasyarakat->abstrak ?? '') }}</textarea>
                            @error('abstrak')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="skema" class="block text-sm font-medium text-gray-700 mb-2">Skema <span class="text-red-600">*</span></label>
                                <input type="text" id="skema" name="skema" value="{{ old('skema', $pengabdianMasyarakat->skema ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Contoh: Program Kemitraan Masyarakat">
                                @error('skema')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="mitra" class="block text-sm font-medium text-gray-700 mb-2">Mitra Sasaran <span class="text-red-600">*</span></label>
                                <input type="text" id="mitra" name="mitra" value="{{ old('mitra', $pengabdianMasyarakat->mitra ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Contoh: UMKM Keripik, Kelompok Tani">
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
                                <input type="text" id="tahun_akademik" name="tahun_akademik" value="{{ old('tahun_akademik', $pengabdianMasyarakat->tahun_akademik ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Cth: 2024/2025">
                                @error('tahun_akademik')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil" {{ old('semester', $pengabdianMasyarakat->semester ?? '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $pengabdianMasyarakat->semester ?? '') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">Jml. Peserta <span class="text-red-600">*</span></label>
                                <input type="number" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', $pengabdianMasyarakat->jumlah_peserta ?? '') }}" min="1" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('jumlah_peserta')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengabdianMasyarakat->tanggal_mulai ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_mulai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengabdianMasyarakat->tanggal_selesai ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_selesai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pendanaan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Pendanaan</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="dana" class="block text-sm font-medium text-gray-700 mb-2">Nominal Dana (Rp)</label>
                                <input type="number" id="dana" name="dana" value="{{ old('dana', $pengabdianMasyarakat->dana ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="0">
                                @error('dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                                <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $pengabdianMasyarakat->sumber_dana ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Mandiri / Hibah / Kampus">
                                @error('sumber_dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Tim Pelaksana --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6"
                     x-data="{ 
                        dosenItems: {{ json_encode($dosenList) }},
                        mahasiswaItems: {{ json_encode($mahasiswaList) }},
                        addDosen() { this.dosenItems.push({nama: '', nip: ''}); },
                        removeDosen(index) { if(this.dosenItems.length > 1) this.dosenItems.splice(index, 1); },
                        addMahasiswa() { this.mahasiswaItems.push({nama: '', nim: ''}); },
                        removeMahasiswa(index) { if(this.mahasiswaItems.length > 1) this.mahasiswaItems.splice(index, 1); }
                     }">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Tim Pelaksana</h2>
                    
                    <div class="space-y-8">
                        
                        {{-- Bagian Dosen --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-gray-800">Dosen</label>
                                <button type="button" @click="addDosen()" class="text-sm text-telkom-blue hover:text-blue-700 font-medium flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Dosen
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <template x-for="(dosen, index) in dosenItems" :key="'dosen-'+index">
                                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 items-end hover:border-blue-200 transition-colors">
                                        <div class="flex-1 w-full">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Dosen</label>
                                            <input type="text" name="anggota[]" x-model="dosen.nama"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                   placeholder="Nama Lengkap Dosen">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">NIP</label>
                                            <input type="text" name="dosen_nip[]" x-model="dosen.nip"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                   placeholder="NIP Dosen">
                                        </div>
                                        <div class="w-auto">
                                            <button type="button"
                                                    @click="removeDosen(index)"
                                                    :disabled="dosenItems.length === 1"
                                                    :class="dosenItems.length === 1 
                                                        ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' 
                                                        : 'bg-white text-red-500 border-red-200 hover:bg-red-50 hover:text-red-600 hover:border-red-300 cursor-pointer shadow-sm'"
                                                    class="w-[38px] h-[38px] flex items-center justify-center border rounded-md transition-all"
                                                    title="Hapus Baris">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Bagian Mahasiswa --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-gray-800">Mahasiswa</label>
                                <button type="button" @click="addMahasiswa()" class="text-sm text-telkom-blue hover:text-blue-700 font-medium flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Mahasiswa
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <template x-for="(mhs, index) in mahasiswaItems" :key="'mhs-'+index">
                                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 items-end hover:border-blue-200 transition-colors">
                                        <div class="flex-1 w-full">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Mahasiswa</label>
                                            <input type="text" name="mahasiswa[]" x-model="mhs.nama"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                   placeholder="Nama Lengkap Mahasiswa">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">NIM</label>
                                            <input type="text" name="mahasiswa_nim[]" x-model="mhs.nim"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-telkom-blue focus:border-telkom-blue text-sm"
                                                   placeholder="NIM Mahasiswa">
                                        </div>
                                        <div class="w-auto">
                                            <button type="button"
                                                    @click="removeMahasiswa(index)"
                                                    :disabled="mahasiswaItems.length === 1"
                                                    :class="mahasiswaItems.length === 1 
                                                        ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' 
                                                        : 'bg-white text-red-500 border-red-200 hover:bg-red-50 hover:text-red-600 hover:border-red-300 cursor-pointer shadow-sm'"
                                                    class="w-[38px] h-[38px] flex items-center justify-center border rounded-md transition-all"
                                                    title="Hapus Baris">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 5: Dokumen & Status --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Dokumen & Status</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Kegiatan <span class="text-red-600">*</span></label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                <option value="proposal" {{ old('status', $pengabdianMasyarakat->status ?? '') == 'proposal' ? 'selected' : '' }}>📋 Proposal (Baru Diajukan)</option>
                                <option value="berjalan" {{ old('status', $pengabdianMasyarakat->status ?? '') == 'berjalan' ? 'selected' : '' }}>🏃 Sedang Berjalan</option>
                                <option value="selesai" {{ old('status', $pengabdianMasyarakat->status ?? '') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="ditolak" {{ old('status', $pengabdianMasyarakat->status ?? '') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                            @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            
                            {{-- File Proposal --}}
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_proposal)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_proposal_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(basename($pengabdianMasyarakat->file_proposal), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_proposal) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>

                                    {{-- Upload area untuk mengganti file --}}
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
                                    {{-- Upload area kosong --}}
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
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_laporan)
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_laporan_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(basename($pengabdianMasyarakat->file_laporan), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_laporan) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
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

                            {{-- Dokumentasi --}}
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (Foto/ZIP, max 10MB)</label>
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_dokumentasi)
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_dokumentasi_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-archive w-6 h-6 text-yellow-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(basename($pengabdianMasyarakat->file_dokumentasi), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_dokumentasi) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
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
                        Simpan Pengmas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle selected file and render a file-card similar to 'penelitian' create
        function handleFileChange(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const existingCard = document.getElementById(cardId);

            if (!input) return;
            input.addEventListener('change', function(e) {
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
            handleFileChange('file_proposal', 'file_proposal_upload', 'file_proposal_card');
            handleFileChange('file_proposal', 'file_proposal_replace', 'file_proposal_card');
            handleFileChange('file_laporan', 'file_laporan_upload', 'file_laporan_card');
            handleFileChange('file_laporan', 'file_laporan_replace', 'file_laporan_card');
            handleFileChange('file_dokumentasi', 'file_dokumentasi_upload', 'file_dokumentasi_card');
            handleFileChange('file_dokumentasi', 'file_dokumentasi_replace', 'file_dokumentasi_card');

            setupDragAndDrop('file_proposal', 'file_proposal_upload');
            setupDragAndDrop('file_proposal', 'file_proposal_replace');
            setupDragAndDrop('file_laporan', 'file_laporan_upload');
            setupDragAndDrop('file_laporan', 'file_laporan_replace');
            setupDragAndDrop('file_dokumentasi', 'file_dokumentasi_upload');
            setupDragAndDrop('file_dokumentasi', 'file_dokumentasi_replace');
        });
    </script>
</x-app-layout>