@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Publikasi</h2>
            <div class="flex space-x-2">
                <a href="{{ route('publikasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
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
                    <h3 class="text-2xl font-bold text-gray-900 leading-tight">{{ $publikasi->judul_publikasi }}</h3>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="mr-4"><i class="far fa-calendar-alt mr-1"></i> {{ $publikasi->tahun }} ({{ ucfirst($publikasi->semester) }})</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($publikasi->jenis) }}</span>
                    </div>
                </div>

                {{-- Penulis Section (DIPERBAIKI) --}}
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Penulis</h4>
                    <div class="rounded-lg p-4 border border-gray-100">
                        <ul class="space-y-2">
                            @forelse($publikasi->penulis as $p)
                                @php
                                    // Logika untuk menangani berbagai format data penulis
                                    $namaPenulis = '-';
                                    
                                    if (is_string($p)) {
                                        // Jika formatnya langsung teks: "Budi Santoso"
                                        $namaPenulis = $p;
                                    } elseif (is_array($p)) {
                                        // Jika formatnya array: ['nama' => 'Budi Santoso']
                                        $namaPenulis = $p['nama'] ?? $p['name'] ?? '-';
                                    } elseif (is_object($p)) {
                                        // Jika formatnya object: {'nama': 'Budi Santoso'}
                                        $namaPenulis = $p->nama ?? $p->name ?? '-';
                                    }
                                @endphp

                                @if(!empty($namaPenulis) && $namaPenulis !== '-')
                                    <li class="flex items-center text-gray-700 bg-white p-2 rounded shadow-sm border border-gray-100">
                                        <i class="fas fa-user-edit text-green-400 mr-2 text-xs"></i>
                                        <span class="font-medium">{{ $namaPenulis }}</span>
                                    </li>
                                @endif
                            @empty
                                <li class="text-gray-400 text-sm italic pl-2">Tidak ada data penulis</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Nama Publikasi</p>
                            <p class="font-medium text-gray-900 text-lg">{{ $publikasi->nama_publikasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Penerbit</p>
                            <p class="font-medium text-gray-900">{{ $publikasi->penerbit ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">ISSN/ISBN</p>
                            <p class="font-medium text-gray-900">{{ $publikasi->issn_isbn ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Tanggal Terbit</p>
                            <p class="font-medium text-gray-900">{{ $publikasi->tanggal_terbit ? Carbon::parse($publikasi->tanggal_terbit)->format('d M Y') : '-' }}</p>
                        </div>
                        @if($publikasi->volume || $publikasi->nomor || $publikasi->halaman)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Volume/Nomor/Halaman</p>
                            <p class="font-medium text-gray-900">
                                @if($publikasi->volume) Vol. {{ $publikasi->volume }} @endif
                                @if($publikasi->nomor) No. {{ $publikasi->nomor }} @endif
                                @if($publikasi->halaman) Hal. {{ $publikasi->halaman }} @endif
                            </p>
                        </div>
                        @endif
                        @if($publikasi->indexing || $publikasi->quartile)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Indexing & Quartile</p>
                            <div class="flex gap-2 mt-1">
                                @if($publikasi->indexing)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ strtoupper($publikasi->indexing) }}</span>
                                @endif
                                @if($publikasi->quartile)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $publikasi->quartile }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($publikasi->doi)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">DOI</p>
                    <p class="font-medium text-gray-900">{{ $publikasi->doi }}</p>
                </div>
                @endif

                @if($publikasi->url)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">URL</p>
                    <a href="{{ $publikasi->url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $publikasi->url }}</a>
                </div>
                @endif

                @if($publikasi->abstrak)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Abstrak</p>
                    <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed border border-gray-100">
                        {{ $publikasi->abstrak }}
                    </div>
                </div>
                @endif
            </div>

            {{-- Dokumen Pendukung --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6 border border-gray-100">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Dokumen Pendukung</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- File Publikasi --}}
                        @if($publikasi->file_publikasi)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <p class="text-sm text-gray-600 mb-2 font-medium">File Publikasi</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('publikasi.download.publikasi', $publikasi) }}" class="text-blue-600 hover:underline flex items-center group">
                                    <svg class="w-5 h-5 mr-2 group-hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($publikasi->file_publikasi)), 30, '...') }}</span>
                                </a>
                            @else
                                <a href="{{ route('publikasi.download.publikasi', $publikasi) }}" target="_blank" class="text-blue-600 hover:underline flex items-center group">
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
                    @if(!$publikasi->file_publikasi)
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Tidak ada file yang terinput</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Verification Status --}}
            <div class="bg-white shadow-lg sm:rounded-xl p-6 border-l-4 
                @if($publikasi->status_verifikasi === 'verified') border-green-500 
                @elseif($publikasi->status_verifikasi === 'rejected') border-red-500 
                @else border-yellow-500 @endif">
                
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Status Verifikasi</h4>
                        <p class="text-sm text-gray-500 mt-1">Status saat ini: 
                            <span class="font-bold 
                                @if($publikasi->status_verifikasi === 'verified') text-green-600 
                                @elseif($publikasi->status_verifikasi === 'rejected') text-red-600 
                                @else text-yellow-600 @endif">
                                {{ ucfirst($publikasi->status_verifikasi) }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-gray-100 p-2 rounded-full">
                        @if($publikasi->status_verifikasi === 'verified') 
                            <i class="fas fa-check text-green-500 text-xl"></i>
                        @elseif($publikasi->status_verifikasi === 'rejected') 
                            <i class="fas fa-times text-red-500 text-xl"></i>
                        @else 
                            <i class="fas fa-clock text-yellow-500 text-xl"></i>
                        @endif
                    </div>
                </div>

                @if($publikasi->verified_by)
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center">
                    <i class="fas fa-user-check text-gray-400 mr-2"></i>
                    <div class="text-sm">
                        <span class="text-gray-500">Diverifikasi oleh:</span>
                        <span class="font-semibold text-gray-900">{{ $publikasi->verifiedBy->name }}</span>
                        <span class="text-gray-400 mx-1">•</span>
                        <span class="text-gray-500">{{ $publikasi->verified_at?->format('d M Y H:i') }}</span>
                    </div>
                </div>
                @endif

                @if($publikasi->catatan_verifikasi)
                <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Catatan</p>
                    <p class="text-gray-800 text-sm">{{ $publikasi->catatan_verifikasi }}</p>
                </div>
                @endif

                {{-- Form Verifikasi (Hanya utk Reviewer) --}}
                @if(auth()->user()->canVerify() && $publikasi->status_verifikasi === 'pending')
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('publikasi.verify', $publikasi) }}" method="POST">
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