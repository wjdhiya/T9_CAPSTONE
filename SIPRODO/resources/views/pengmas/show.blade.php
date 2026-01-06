@php
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengabdian Masyarakat</h2>
            <div class="flex space-x-2">
                <a href="{{ route('pengmas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">{{ session('success') }}</div>
            @endif

            {{-- Main Content --}}
            <div class="bg-white shadow-lg sm:rounded-xl mb-6 p-8 border border-gray-100">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pengabdianMasyarakat->judul_pkm }}</h3>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="mr-4"><i class="far fa-calendar-alt mr-1"></i> {{ $pengabdianMasyarakat->tahun }} ({{ ucfirst($pengabdianMasyarakat->semester) }})</span>
                        <span><i class="fas fa-lightbulb mr-1"></i> {{ $pengabdianMasyarakat->skema }}</span>
                    </div>
                </div>
                
                {{-- Deskripsi --}}
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Kegiatan</h4>
                    <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed border border-gray-100">
                        {{ $pengabdianMasyarakat->abstrak }}
                    </div>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Mitra Sasaran</p>
                            <p class="font-medium text-gray-900 text-lg">{{ $pengabdianMasyarakat->mitra }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Jumlah Peserta</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->jumlah_peserta ?? '-' }} Orang</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Jenis Hibah</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($pengabdianMasyarakat->jenis_hibah) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">SDG</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->sdg ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Kesesuaian Roadmap KK</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->kesesuaian_roadmap_kk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Tipe Pendanaan</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->tipe_pendanaan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Status Kegiatan</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->status_kegiatan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Periode Pelaksanaan</p>
                            <div class="flex items-center text-gray-900 font-medium">
                                <span>{{ $pengabdianMasyarakat->tanggal_mulai ? \Carbon\Carbon::parse($pengabdianMasyarakat->tanggal_mulai)->format('d M Y') : '-' }}</span>
                                <span class="mx-2 text-gray-400">s/d</span>
                                <span>{{ $pengabdianMasyarakat->tanggal_selesai ? \Carbon\Carbon::parse($pengabdianMasyarakat->tanggal_selesai)->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Sumber Dana</p>
                            <p class="font-medium text-gray-900">{{ $pengabdianMasyarakat->sumber_dana ?? 'Mandiri' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Anggaran</p>
                            <p class="font-medium text-gray-900">@if($pengabdianMasyarakat->anggaran) Rp {{ number_format($pengabdianMasyarakat->anggaran, 0, ',', '.') }} @else - @endif</p>
                        </div>
                    </div>
                </div>

                {{-- Logic Pemrosesan Tim --}}
                @php
                    $processList = function($data) {
                        if (empty($data)) return [];
                        if (is_string($data)) {
                            // Coba decode JSON, jika gagal anggap CSV
                            $decoded = json_decode($data, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                return $decoded;
                            }
                            return array_map('trim', explode(',', $data));
                        }
                        if (is_array($data)) return $data;
                        return [];
                    };

                    $anggotaDosen = $processList($pengabdianMasyarakat->tim_abdimas);
                    $dosenNip = $processList($pengabdianMasyarakat->dosen_nip);
                    $anggotaMahasiswa = $processList($pengabdianMasyarakat->anggota_mahasiswa ?? $pengabdianMasyarakat->mahasiswa);
                    $mahasiswaNim = $processList($pengabdianMasyarakat->mahasiswa_nim);
                @endphp

                {{-- Tim Pelaksana Section --}}
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Tim Pelaksana</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Dosen --}}
                        <div class="rounded-lg p-4 border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="flex items-center justify-center mr-3">
                                </div>
                                <h5 class="font-bold text-gray-900">Dosen</h5>
                            </div>
                            <ul class="space-y-2">
                                @forelse($anggotaDosen as $index => $dosen)
                                    @if(!empty($dosen))
                                        <li class="bg-white p-3 rounded shadow-sm border border-gray-100">
                                            <div class="flex items-start">
                                                <i class="fas fa-user-tie text-blue-400 mr-2 text-sm mt-1"></i> 
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $dosen }}</div>
                                                    @if(isset($dosenNip[$index]) && !empty($dosenNip[$index]))
                                                        <div class="text-xs text-gray-500 mt-1">NIP: {{ $dosenNip[$index] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-gray-400 text-sm italic pl-2">Tidak ada data dosen</li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Mahasiswa --}}
                        <div class="rounded-lg p-4 border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="flex items-center justify-center mr-3">
                                </div>
                                <h5 class="font-bold text-gray-900">Anggota Mahasiswa</h5>
                            </div>
                            <ul class="space-y-2">
                                @forelse($anggotaMahasiswa as $index => $mahasiswa)
                                    @if(!empty($mahasiswa))
                                        <li class="bg-white p-3 rounded shadow-sm border border-gray-100">
                                            <div class="flex items-start">
                                                <i class="fas fa-user text-green-400 mr-2 text-sm mt-1"></i> 
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $mahasiswa }}</div>
                                                    @if(isset($mahasiswaNim[$index]) && !empty($mahasiswaNim[$index]))
                                                        <div class="text-xs text-gray-500 mt-1">NIM: {{ $mahasiswaNim[$index] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @empty
                                    <li class="text-gray-400 text-sm italic pl-2">Tidak ada data mahasiswa</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Status Kegiatan --}}
                <div class="mb-4">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Status Kegiatan</p>
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg
                        @if($pengabdianMasyarakat->status === 'selesai') bg-green-100 text-green-800 border border-green-200
                        @elseif($pengabdianMasyarakat->status === 'berjalan') bg-yellow-100 text-yellow-800 border border-yellow-200
                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                        {{ ucfirst($pengabdianMasyarakat->status) }}
                    </span>
                </div>
            </div>

            {{-- Dokumen Pendukung --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6 border border-gray-100">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Dokumen Pendukung</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Proposal --}}
                        @if($pengabdianMasyarakat->file_proposal)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <p class="text-sm text-gray-600 mb-2 font-medium">File Proposal</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('pengmas.download.proposal', $pengabdianMasyarakat) }}" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_proposal)), 30, '...') }}</span>
                                </a>
                            @else
                                <a href="{{ route('pengmas.download.proposal', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="font-medium">View File</span>
                                </a>
                            @endif
                        </div>
                        @endif

                        {{-- Laporan --}}
                        @if($pengabdianMasyarakat->file_laporan)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <p class="text-sm text-gray-600 mb-2 font-medium">File Laporan</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('pengmas.download.laporan', $pengabdianMasyarakat) }}" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_laporan)), 30, '...') }}</span>
                                </a>
                            @else
                                <a href="{{ route('pengmas.download.laporan', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="font-medium">View File</span>
                                </a>
                            @endif
                        </div>
                        @endif

                        {{-- Dokumentasi --}}
                        @if($pengabdianMasyarakat->file_dokumentasi)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <p class="text-sm text-gray-600 mb-2 font-medium">File Dokumentasi</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('pengmas.download.dokumentasi', $pengabdianMasyarakat) }}" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_dokumentasi)), 30, '...') }}</span>
                                </a>
                            @else
                                <a href="{{ route('pengmas.download.dokumentasi', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="font-medium">View File</span>
                                </a>
                            @endif
                        </div>
                        @endif

                    </div>
                    
                    {{-- Pesan jika tidak ada file --}}
                    @if(!$pengabdianMasyarakat->file_proposal && !$pengabdianMasyarakat->file_laporan && !$pengabdianMasyarakat->file_dokumentasi)
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Tidak ada file yang terinput</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Verification Status Box -->
            <div class="bg-white shadow-lg sm:rounded-xl p-6 border-l-4 
                @if($pengabdianMasyarakat->status_verifikasi === 'verified') border-green-500 
                @elseif($pengabdianMasyarakat->status_verifikasi === 'rejected') border-red-500 
                @else border-yellow-500 @endif">
                
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Status Verifikasi</h4>
                        <p class="text-sm text-gray-500 mt-1">Status saat ini: 
                            <span class="font-bold 
                                @if($pengabdianMasyarakat->status_verifikasi === 'verified') text-green-600 
                                @elseif($pengabdianMasyarakat->status_verifikasi === 'rejected') text-red-600 
                                @else text-yellow-600 @endif">
                                {{ ucfirst($pengabdianMasyarakat->status_verifikasi) }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-gray-100 p-2 rounded-full">
                        @if($pengabdianMasyarakat->status_verifikasi === 'verified') 
                            <i class="fas fa-check text-green-500 text-xl"></i>
                        @elseif($pengabdianMasyarakat->status_verifikasi === 'rejected') 
                            <i class="fas fa-times text-red-500 text-xl"></i>
                        @else 
                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                        @endif
                    </div>
                </div>

                @if($pengabdianMasyarakat->verified_by)
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center">
                    <i class="fas fa-user-check text-gray-400 mr-2"></i>
                    <div class="text-sm">
                        <span class="text-gray-500">Diverifikasi oleh:</span>
                        <span class="font-semibold text-gray-900">{{ $pengabdianMasyarakat->verifiedBy->name }}</span>
                        <span class="text-gray-400 mx-1">•</span>
                        <span class="text-gray-500">{{ $pengabdianMasyarakat->verified_at?->format('d M Y H:i') }}</span>
                    </div>
                </div>
                @endif

                @if($pengabdianMasyarakat->catatan_verifikasi)
                <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Catatan</p>
                    <p class="text-gray-800 text-sm">{{ $pengabdianMasyarakat->catatan_verifikasi }}</p>
                </div>
                @endif

                {{-- Form Verifikasi (Hanya utk Reviewer) --}}
                @if(auth()->user()->canVerify() && $pengabdianMasyarakat->status_verifikasi === 'pending')
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('pengmas.verify', $pengabdianMasyarakat) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Berikan Catatan (Opsional)</label>
                            <textarea name="catatan_verifikasi" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tulis alasan disetujui atau ditolak..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" name="status_verifikasi" value="verified" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm font-semibold flex justify-center items-center">
                                <i class="fas fa-check mr-2"></i> Setujui
                            </button>
                            <button type="submit" name="status_verifikasi" value="rejected" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm font-semibold flex justify-center items-center">
                                <i class="fas fa-times mr-2"></i> Tolak
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>