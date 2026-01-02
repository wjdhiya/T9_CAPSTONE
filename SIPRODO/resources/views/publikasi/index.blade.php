<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900" style="color: #003366;">
                {{ __('Data Publikasi') }}
            </h1>
            {{-- Tombol Tambah --}}
            @if(auth()->user()->canInputTriDharma())
                <a href="{{ route('publikasi.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <i class="fas fa-plus w-5 h-5"></i>
                    Tambah Publikasi
                </a>
            @endif
        </div>
        <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua publikasi ilmiah Anda</p>
    </x-slot>

    <div class="py-12" style="background-color: #f8f8f8;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian Statistik (Stat Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                {{-- Total Publikasi --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Publikasi</span>
                        <i class="fas fa-book-open w-5 h-5 text-gray-400"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                {{-- Verified --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-green-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Verified</span>
                        <i class="fas fa-check w-5 h-5 text-green-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['verified'] ?? 0 }}</p>
                </div>
                {{-- Pending --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Pending</span>
                        <i class="fas fa-exclamation-triangle w-5 h-5 text-yellow-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                {{-- Scopus/WoS --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Scopus/WoS</span>
                        <i class="fas fa-award w-5 h-5 text-indigo-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['high_impact'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6 mb-6">
                {{-- Form Filter --}}
                <form method="GET" action="{{ route('publikasi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        {{-- Search Input --}}
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input
                                    id="search"
                                    type="text"
                                    name="search"
                                    placeholder="Cari judul publikasi atau nama penulis..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                                />
                            </div>
                        </div>
                        {{-- Filter Jenis --}}
                        <div>
                            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Publikasi</label>
                            <select id="jenis" name="jenis" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                                <option value="">Semua Jenis</option>
                                <option value="jurnal" {{ request('jenis') == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                                <option value="prosiding" {{ request('jenis') == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                                <option value="buku" {{ request('jenis') == 'buku' ? 'selected' : '' }}>Buku</option>
                                <option value="chapter" {{ request('jenis') == 'chapter' ? 'selected' : '' }}>Book Chapter</option>
                            </select>
                        </div>
                        {{-- Filter Indexing --}}
                        <div>
                            <label for="indexing" class="block text-sm font-medium text-gray-700 mb-1">Indexing</label>
                            <select id="indexing" name="indexing" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                                <option value="">Semua Indexing</option>
                                <option value="scopus" {{ request('indexing') == 'scopus' ? 'selected' : '' }}>Scopus</option>
                                <option value="wos" {{ request('indexing') == 'wos' ? 'selected' : '' }}>Web of Science</option>
                                <option value="sinta" {{ request('indexing') == 'sinta' ? 'selected' : '' }}>SINTA</option>
                                <option value="doaj" {{ request('indexing') == 'doaj' ? 'selected' : '' }}>DOAJ</option>
                                <option value="google-scholar" {{ request('indexing') == 'google-scholar' ? 'selected' : '' }}>Google Scholar</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            {{-- Filter Status --}}
                            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white text-sm">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="pending" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="rejected" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            
                            {{-- Tombol Filter/Search --}}
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium">
                                <i class="fas fa-filter w-4 h-4 mr-1"></i> Filter
                            </button>
                            
                            {{-- Tombol Reset --}}
                            @if(request('search') || request('jenis') || request('indexing') || request('status'))
                                <a href="{{ route('publikasi.index') }}" class="flex items-center gap-1 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border border-gray-300">
                                    <i class="fas fa-sync-alt w-4 h-4"></i> Reset
                                </a>
                            @endif
                        </div>
                        <p class="hidden md:block text-sm text-gray-500">
                            Menampilkan {{ $publikasi->firstItem() }}-{{ $publikasi->lastItem() }} dari {{ $publikasi->total() }} publikasi
                        </p>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">JUDUL</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PENULIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">JENIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">INDEXING</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TAHUN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($publikasi as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Kolom Judul --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm font-bold text-gray-900 line-clamp-2" title="{{ $item->judul }}">{{ $item->judul }}</p>
                                            <p class="text-xs text-gray-500 truncate mt-1 flex items-center gap-1">
                                                <i class="fas fa-building w-3 h-3"></i> {{ $item->penerbit }}
                                            </p>
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom Penulis dengan Fallback --}}
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">
                                            {{ !empty($item->penulis) ? Str::limit($item->penulis, 30) : ($item->user->name ?? '-') }}
                                        </p>
                                    </td>
                                    
                                    {{-- Kolom Jenis --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $jenisClass = [
                                                'jurnal' => 'bg-blue-100 text-blue-700',
                                                'prosiding' => 'bg-purple-100 text-purple-700',
                                                'buku' => 'bg-orange-100 text-orange-700',
                                                'chapter' => 'bg-pink-100 text-pink-700'
                                            ][$item->jenis] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $jenisClass }}">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    
                                    {{-- Kolom Indexing --}}
                                    <td class="px-6 py-4">
                                        @if ($item->indexing)
                                            <div class="flex items-center gap-1">
                                                <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded">{{ strtoupper($item->indexing) }}</span>
                                                @if ($item->quartile)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700 font-bold border border-indigo-200">
                                                        {{ strtoupper($item->quartile) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom Tahun --}}
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ $item->tahun_akademik }}</p>
                                    </td>
                                    
                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = '';
                                            switch ($item->status_verifikasi) {
                                                case 'verified':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle"></i> Disetujui</span>';
                                                    break;
                                                case 'pending':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock"></i> Menunggu</span>';
                                                    break;
                                                case 'rejected':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle"></i> Ditolak</span>';
                                                    break;
                                            }
                                        @endphp
                                        {!! $statusBadge !!}
                                    </td>
                                    
                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('publikasi.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                        
                                        {{-- LOGIKA DIUBAH: Tombol Edit & Hapus HANYA muncul jika status BUKAN verified DAN BUKAN rejected --}}
                                        @if (!in_array($item->status_verifikasi, ['verified', 'rejected']) && auth()->user()->canInputTriDharma() && $item->user_id === auth()->id())
                                            <a href="{{ route('publikasi.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            
                                            <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus data ini?')) document.getElementById('delete-form-{{ $item->id }}').submit()" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                            
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('publikasi.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-3">
                                                <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-900">Belum ada data publikasi</p>
                                            <p class="text-sm text-gray-500">Silakan tambahkan data publikasi baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $publikasi->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>