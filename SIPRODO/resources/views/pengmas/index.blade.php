<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-hand-holding-heart mr-2 text-indigo-600"></i>Data Pengabdian Masyarakat
            </h2>
            @if(auth()->user()->canInputTriDharma())
                <a href="{{ route('pengmas.create') }}" class="px-4 py-2 text-white rounded-lg bg-telkom-blue hover:bg-blue-800 transition-colors shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Pengmas
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center" role="alert">
                    <div>
                        <span class="font-bold">Berhasil!</span>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- FILTER SECTION -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('pengmas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    
                    {{-- Input Pencarian --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                placeholder="Cari judul, dosen, skema..." 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    {{-- Filter Tahun --}}
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select id="tahun" name="tahun" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Tahun</option>
                            @php $currentYear = date('Y'); @endphp
                            @for($year = $currentYear; $year >= $currentYear - 5; $year--)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pelaksanaan</label>
                        <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Tombol Filter & Reset --}}
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium">
                            Filter
                        </button>
                        {{-- Tombol Reset hanya muncul jika ada filter aktif --}}
                        @if(request()->hasAny(['search', 'tahun', 'status']))
                            <a href="{{ route('pengmas.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition-colors border border-gray-300" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 pb-0">
                    @if(auth()->user()->isAdmin())
                    <div class="mb-4 flex space-x-2">
                         <form id="bulk-delete-form" action="{{ route('pengmas.bulk_destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?');">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                                Hapus Terpilih
                            </button>
                        </form>
                        
                        <form action="{{ route('pengmas.empty_table') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA data pengabdian masyarakat? Tindakan ini tidak dapat dibatalkan!');">
                            @csrf
                            <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded shadow text-sm ml-2">
                                Kosongkan Semua Data
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    {{-- Form wrapper for table checkboxes --}}
                    <form id="table-bulk-form" action="{{ route('pengmas.bulk_destroy') }}" method="POST">
                        @csrf
                        
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if(auth()->user()->isAdmin())
                                    <th scope="col" class="px-6 py-3 text-left">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul PKM & Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema & Mitra</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Hibah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SDG</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kesesuaian Roadmap KK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Pendanaan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kegiatan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengmas as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if(auth()->user()->isAdmin())
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 line-clamp-2" title="{{ $item->judul_pkm }}">
                                        {{ $item->judul_pkm }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center">
                                        {{-- Lingkaran avatar dihapus di sini --}}
                                        {{ $item->user->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col space-y-1">
                                        <span class="flex items-center" title="Skema">
                                            <i class="fas fa-lightbulb text-gray-400 w-4 mr-1"></i> 
                                            {{ Str::limit($item->skema, 20) }}
                                        </span>
                                        <span class="flex items-center text-xs text-gray-500" title="Mitra">
                                            <i class="fas fa-handshake text-gray-400 w-4 mr-1"></i> 
                                            {{ Str::limit($item->mitra, 20) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="font-medium">{{ $item->tahun }}</div>
                                    <span class="text-xs text-gray-400">{{ ucfirst($item->semester) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($item->jenis_hibah) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->sdg ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->kesesuaian_roadmap_kk ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->tipe_pendanaan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->status_kegiatan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        {{-- Badge Verifikasi --}}
                                        <div>
                                            @if($item->status_verifikasi === 'verified')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1 mt-0.5"></i> Disetujui
                                                </span>
                                            @elseif($item->status_verifikasi === 'rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1 mt-0.5"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1 mt-0.5"></i> Menunggu
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Badge Pelaksanaan --}}
                                        <div class="text-xs text-gray-500 pl-1">
                                            Status: <span class="font-medium">{{ ucfirst($item->status) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center items-center space-x-3">
                                        {{-- Ganti ikon dengan tautan teks "Lihat" --}}
                                        <a href="{{ route('pengmas.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Lihat Detail">
                                            Lihat
                                        </a>
                                        
                                        {{-- LOGIKA DIUBAH: Tombol Edit & Hapus disembunyikan jika status VERIFIED atau REJECTED --}}
                                        @if (!in_array($item->status_verifikasi, ['verified', 'rejected']) && auth()->user()->canInputTriDharma() && $item->user_id === auth()->id())
                                            <a href="{{ route('pengmas.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <button type="button" onclick="document.getElementById('delete-form-{{ $item->id }}').submit()" class="text-red-600 hover:text-red-900">Hapus</button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('pengmas.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 rounded-full p-4 mb-3">
                                            <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-900">Belum ada data</p>
                                        <p class="text-sm text-gray-500">Silakan tambahkan data pengabdian masyarakat baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </form>
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
                        
                        // Hook up the separate form button to submit the table form
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
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $pengmas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
