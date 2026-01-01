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
    // Cek kedua kemungkinan nama kolom: 'mahasiswa' atau 'mahasiswa_terlibat'
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
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
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
                                                        title="Hapus Dosen">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addDosen()" class="w-full mt-3 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors flex justify-center items-center">
                                        <i class="fas fa-plus mr-2"></i> Tambah Dosen
                                    </button>
                                </div>

                                {{-- Kolom Mahasiswa --}}
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                    <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-user-graduate mr-1"></i> Nama Mahasiswa</label>
                                    <div class="space-y-3">
                                        <template x-for="(mhs, index) in mahasiswaItems" :key="'mhs-'+index">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="mahasiswa[]" x-model="mahasiswaItems[index]"
                                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 bg-white"
                                                       placeholder="Nama Mahasiswa">
                                                
                                                <button type="button" 
                                                        @click="removeMahasiswa(index)" 
                                                        :disabled="mahasiswaItems.length === 1"
                                                        class="p-2.5 rounded-lg transition-colors border border-gray-200" 
                                                        :class="mahasiswaItems.length === 1 ? 'text-gray-300 cursor-not-allowed bg-gray-50' : 'text-red-500 hover:bg-red-50 hover:border-red-200 cursor-pointer'"
                                                        title="Hapus Mahasiswa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addMahasiswa()" class="w-full mt-3 py-2 text-sm font-medium text-green-600 bg-white border border-green-300 rounded-lg hover:bg-green-50 transition-colors flex justify-center items-center">
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                @if($pengabdianMasyarakat->file_proposal)
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg mb-2">
                                        <p class="text-xs text-blue-800 break-all mb-1">
                                            <i class="fas fa-file-pdf mr-1"></i> {{ basename($pengabdianMasyarakat->file_proposal) }}
                                        </p>
                                    </div>
                                @endif
                                <input type="file" name="file_proposal" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer"/>
                                @error('file_proposal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- File Laporan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF, max 10MB)</label>
                                @if($pengabdianMasyarakat->file_laporan)
                                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg mb-2">
                                        <p class="text-xs text-red-800 break-all mb-1">
                                            <i class="fas fa-file-pdf mr-1"></i> {{ basename($pengabdianMasyarakat->file_laporan) }}
                                        </p>
                                    </div>
                                @endif
                                <input type="file" name="file_laporan" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer"/>
                                @error('file_laporan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Dokumentasi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (Foto/ZIP, max 10MB)</label>
                                @if($pengabdianMasyarakat->file_dokumentasi)
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg mb-2">
                                        <p class="text-xs text-yellow-800 break-all mb-1">
                                            <i class="fas fa-file-archive mr-1"></i> {{ basename($pengabdianMasyarakat->file_dokumentasi) }}
                                        </p>
                                    </div>
                                @endif
                                <input type="file" name="file_dokumentasi" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer"/>
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
</x-app-layout>