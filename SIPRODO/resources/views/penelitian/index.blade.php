<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900" style="color: #003366;">
                {{ __('Data Penelitian') }}
            </h1>
            {{-- Tombol Tambah --}}
            @if(auth()->user()->canInputTriDharma())
                <a href="{{ route('penelitian.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <i class="fas fa-plus w-5 h-5"></i>
                    Tambah Penelitian
                </a>
            @endif
        </div>
        <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua kegiatan penelitian Anda</p>
    </x-slot>

    <div class="py-12" style="background-color: #f8f8f8;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center" role="alert">
                    <div>
                        <span class="font-bold">Berhasil!</span>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            {{-- Bagian Statistik (Stat Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                {{-- Total Penelitian --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Total Penelitian</span>
                        <i class="fas fa-flask w-5 h-5 text-gray-400"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? $penelitian->total() }}</p>
                </div>
                {{-- Verified --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-green-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Disetujui</span>
                        <i class="fas fa-check-circle w-5 h-5 text-green-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['verified'] ?? 0 }}</p>
                </div>
                {{-- Pending --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Menunggu</span>
                        <i class="fas fa-clock w-5 h-5 text-yellow-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                {{-- Selesai --}}
                <div class="bg-white rounded-xl shadow-md p-5 border-t-4 border-blue-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Status Selesai</span>
                        <i class="fas fa-flag-checkered w-5 h-5 text-blue-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('penelitian.index') }}">
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
                                    placeholder="Cari judul penelitian atau nama anggota..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                                />
                            </div>
                        </div>
                        
                        {{-- Filter Tahun --}}
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Pelaksanaan</label>
                            <select id="tahun" name="tahun" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                                <option value="">Semua Tahun</option>
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Filter Status Pelaksanaan --}}
                        <div>
                            <label for="status_pelaksanaan" class="block text-sm font-medium text-gray-700 mb-1">Status Kegiatan</label>
                            <select id="status_pelaksanaan" name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white">
                                <option value="">Semua Status</option>
                                <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            {{-- Filter Status Verifikasi --}}
                            <select name="status_verifikasi" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none bg-white text-sm">
                                <option value="">Semua Verifikasi</option>
                                <option value="verified" {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Disetujui</option>
                                <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="rejected" {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            
                            {{-- Tombol Filter --}}
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium">
                                <i class="fas fa-filter w-4 h-4 mr-1"></i> Filter
                            </button>
                            
                            {{-- Tombol Reset --}}
                            @if(request('search') || request('tahun') || request('status') || request('status_verifikasi'))
                                <a href="{{ route('penelitian.index') }}" class="flex items-center gap-1 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border border-gray-300">
                                    <i class="fas fa-sync-alt w-4 h-4"></i> Reset
                                </a>
                            @endif
                        </div>
                        <p class="hidden md:block text-sm text-gray-500">
                            Menampilkan {{ $penelitian->firstItem() }}-{{ $penelitian->lastItem() }} dari {{ $penelitian->total() }} penelitian
                        </p>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="p-6 pb-0">
                    @if(auth()->user()->isAdmin())
                    <div class="mb-4 flex space-x-2">
                         <form id="bulk-delete-form" action="{{ route('penelitian.bulk_destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?');">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                                Hapus Terpilih
                            </button>
                        </form>
                        
                        <form action="{{ route('penelitian.empty_table') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA data penelitian? Tindakan ini tidak dapat dibatalkan!');">
                            @csrf
                            <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded shadow text-sm ml-2">
                                Kosongkan Semua Data
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <form id="table-bulk-form" action="{{ route('penelitian.bulk_destroy') }}" method="POST">
                        @csrf
                    
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-6 py-4 text-left">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                @endif
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Penelitian</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota/Tim</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema & Tahun</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Kegiatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verifikasi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($penelitian as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </td>
                                    @endif

                                    {{-- Kolom Judul --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm font-bold text-gray-900 line-clamp-2" title="{{ $item->judul_penelitian }}">{{ $item->judul_penelitian }}</p>
                                            <p class="text-xs text-gray-500 truncate mt-1 flex items-center gap-1">
                                                <i class="fas fa-university w-3 h-3"></i> {{ $item->sumber_dana ?? 'Mandiri' }}
                                            </p>
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom Anggota (Fixed Logic) --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @php
                                                $anggotaOutput = $item->user->name ?? '-'; // Default
                                                
                                                // Simpan ke variabel lokal dulu
                                                $listAnggota = $item->anggota; 

                                                if (!empty($listAnggota) && is_array($listAnggota)) {
                                                    $first = $listAnggota[0] ?? null; 

                                                    if (is_string($first)) {
                                                        // Kasus A: Array string
                                                        $anggotaOutput = implode(', ', $listAnggota);
                                                    } else {
                                                        // Kasus B: Array object [{"nama": "Budi"}, ...]
                                                        $names = array_map(function($p) {
                                                            $p = (array)$p; 
                                                            return $p['nama'] ?? $p['name'] ?? '';
                                                        }, $listAnggota);
                                                        
                                                        $anggotaOutput = implode(', ', array_filter($names));
                                                    }
                                                }
                                            @endphp
                                            {{ Str::limit($anggotaOutput, 30) }}
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom Skema & Tahun --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 w-fit mb-1">
                                                {{ ucfirst($item->jenis_penelitian ?? 'Penelitian') }}
                                            </span>
                                            <span class="text-sm text-gray-700 flex items-center gap-1">
                                                <i class="far fa-calendar-alt text-xs"></i> {{ $item->tahun }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    {{-- Kolom Status Kegiatan --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClass = match($item->status) {
                                                'selesai' => 'bg-green-100 text-green-800',
                                                'berjalan' => 'bg-yellow-100 text-yellow-800',
                                                'proposal' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    
                                    {{-- Kolom Status Verifikasi --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $verifBadge = '';
                                            switch ($item->status_verifikasi) {
                                                case 'verified':
                                                case 'disetujui':
                                                    $verifBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle"></i> Disetujui</span>';
                                                    break;
                                                case 'pending':
                                                case 'menunggu':
                                                    $verifBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock"></i> Menunggu</span>';
                                                    break;
                                                case 'rejected':
                                                case 'ditolak':
                                                    $verifBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle"></i> Ditolak</span>';
                                                    break;
                                                default:
                                                    $verifBadge = '<span class="text-gray-500 text-xs">-</span>';
                                            }
                                        @endphp
                                        {!! $verifBadge !!}
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('penelitian.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                        
                                        {{-- LOGIKA DIUBAH: Dosen bisa edit/hapus selama status BUKAN verified/disetujui --}}
                                        {{-- Dan pastikan User ID sesuai (Gunakan == agar aman string vs int) --}}
                                        
                                        @if (!in_array($item->status_verifikasi, ['verified', 'disetujui']) && 
                                             auth()->user()->canInputTriDharma() && 
                                             $item->user_id == auth()->id())
                                             
                                            <a href="{{ route('penelitian.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            
                                            <button type="button" 
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus data ini?')) document.getElementById('delete-form-{{ $item->id }}').submit()" 
                                                class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-3">
                                                <i class="fas fa-microscope text-gray-400 text-3xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-900">Belum ada data penelitian</p>
                                            <p class="text-sm text-gray-500">Silakan tambahkan data penelitian baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </form>

                    {{-- Delete Forms (Di luar bulk form untuk menghindari nested form) --}}
                    @foreach ($penelitian as $item)
                        @if (!in_array($item->status_verifikasi, ['verified', 'disetujui']) && 
                             auth()->user()->canInputTriDharma() && 
                             $item->user_id == auth()->id())
                            <form id="delete-form-{{ $item->id }}" action="{{ route('penelitian.destroy', $item->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endforeach
                </div>

                @if(auth()->user()->isAdmin())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const selectAll = document.getElementById('select-all');
                        const items = document.querySelectorAll('.item-checkbox');

                        if(selectAll) {
                            selectAll.addEventListener('change', function() {
                                items.forEach(item => {
                                    item.checked = selectAll.checked;
                                });
                            });
                        }
                        
                        const bulkDeleteForm = document.getElementById('bulk-delete-form');
                        if(bulkDeleteForm) {
                            bulkDeleteForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                if(confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                                    document.getElementById('table-bulk-form').submit();
                                }
                            });
                        }
                    });
                </script>
                @endif

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        @if(request('show_all') !== '1')
                            {{ $penelitian->withQueryString()->links() }}
                        @else
                            <p class="text-sm text-gray-700">Showing all {{ $penelitian->count() }} records</p>
                        @endif
                    </div>
                    <div>
                        @if(request('show_all') !== '1')
                            <a href="{{ request()->fullUrlWithQuery(['show_all' => '1']) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-list mr-2"></i> Show All
                            </a>
                        @else
                            <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-th mr-2"></i> Show Paginated
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>