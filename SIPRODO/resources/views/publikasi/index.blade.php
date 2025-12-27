<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900" style="color: #003366;">
                {{ __('Data Publikasi') }}
            </h1>
            <a href="{{ route('publikasi.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                <i class="fas fa-plus w-5 h-5"></i>
                Tambah Publikasi
            </a>
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
                <form method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        {{-- Search Input --}}
                        <div class="md:col-span-2">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari judul publikasi atau nama penulis..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                                />
                            </div>
                        </div>
                        {{-- Filter Jenis --}}
                        <select name="jenis" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                            <option value="">Semua Jenis</option>
                            <option value="jurnal" {{ request('jenis') == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                            <option value="prosiding" {{ request('jenis') == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                            <option value="buku" {{ request('jenis') == 'buku' ? 'selected' : '' }}>Buku</option>
                            <option value="chapter" {{ request('jenis') == 'chapter' ? 'selected' : '' }}>Book Chapter</option>
                        </select>
                        {{-- Filter Indexing --}}
                        <select name="indexing" class="px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                            <option value="">Semua Indexing</option>
                            <option value="scopus" {{ request('indexing') == 'scopus' ? 'selected' : '' }}>Scopus</option>
                            <option value="wos" {{ request('indexing') == 'wos' ? 'selected' : '' }}>Web of Science</option>
                            <option value="sinta" {{ request('indexing') == 'sinta' ? 'selected' : '' }}>SINTA</option>
                            <option value="doaj" {{ request('indexing') == 'doaj' ? 'selected' : '' }}>DOAJ</option>
                            <option value="google-scholar" {{ request('indexing') == 'google-scholar' ? 'selected' : '' }}>Google Scholar</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            {{-- Filter Status --}}
                            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white text-sm">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            {{-- Tombol Filter/Search --}}
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                                <i class="fas fa-filter w-4 h-4 mr-1"></i> Filter
                            </button>
                            
                            {{-- Tombol Reset (Hanya muncul jika ada filter aktif) --}}
                            @if(request('search') || request('jenis') || request('indexing') || request('status'))
                                <a href="{{ route('publikasi.index') }}" class="flex items-center gap-1 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-sync-alt w-4 h-4"></i> Reset
                                </a>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">
                            {{-- Menampilkan 1-5 dari 10 publikasi (Diserahkan ke Pagination Controller) --}}
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">JUDUL</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">PENULIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">JENIS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">INDEXING</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">TAHUN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">STATUS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($publikasi as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm text-gray-900 truncate">{{ $item->judul }}</p>
                                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ $item->penerbit }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ Str::limit($item->penulis, 30) }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $jenisClass = [
                                                'jurnal' => 'bg-blue-100 text-blue-700',
                                                'prosiding' => 'bg-purple-100 text-purple-700',
                                                'buku' => 'bg-orange-100 text-orange-700',
                                                'chapter' => 'bg-pink-100 text-pink-700'
                                            ][$item->jenis] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs {{ $jenisClass }}">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->indexing)
                                            <div class="flex items-center gap-1">
                                                <span class="text-xs">{{ strtoupper($item->indexing) }}</span>
                                                @if ($item->quartile)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700">
                                                        {{ strtoupper($item->quartile) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ $item->tahun_akademik }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = '';
                                            switch ($item->status_verifikasi) {
                                                case 'verified':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-green-100 text-green-700"><i class="fas fa-check w-3 h-3"></i> Verified</span>';
                                                    break;
                                                case 'pending':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700"><i class="fas fa-exclamation-triangle w-3 h-3"></i> Pending</span>';
                                                    break;
                                                case 'rejected':
                                                    $statusBadge = '<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-red-100 text-red-700"><i class="fas fa-times w-3 h-3"></i> Rejected</span>';
                                                    break;
                                            }
                                        @endphp
                                        {!! $statusBadge !!}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('publikasi.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                        @if ($item->status_verifikasi !== 'verified' && auth()->user()->isDosen() && $item->user_id === auth()->id())
                                            <a href="{{ route('publikasi.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <button type="button" onclick="document.getElementById('delete-form-{{ $item->id }}').submit()" class="text-red-600 hover:text-red-900">Hapus</button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('publikasi.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 text-lg">Tidak ada data publikasi yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $publikasi->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>