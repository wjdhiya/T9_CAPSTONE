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
                                placeholder="Cari judul, lokasi..." 
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    {{-- Filter Tahun --}}
                    <div>
                        <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-1">Tahun Akademik</label>
                        <select id="tahun_akademik" name="tahun_akademik" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Tahun</option>
                            @php $currentYear = date('Y'); @endphp
                            @for($year = $currentYear; $year >= $currentYear - 5; $year--)
                                <option value="{{ $year }}" {{ request('tahun_akademik') == $year ? 'selected' : '' }}>
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
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Tombol Filter & Reset --}}
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium">
                            Filter
                        </button>
                        {{-- Tombol Reset hanya muncul jika ada filter aktif --}}
                        @if(request()->hasAny(['search', 'tahun_akademik', 'status']))
                            <a href="{{ route('pengmas.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 transition-colors border border-gray-300" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul & Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Mitra</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengmas as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 line-clamp-2" title="{{ $item->judul }}">
                                        {{ $item->judul }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-2 text-xs">
                                            {{ substr($item->user->name ?? '?', 0, 1) }}
                                        </div>
                                        {{ $item->user->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col space-y-1">
                                        <span class="flex items-center" title="Lokasi">
                                            <i class="fas fa-map-marker-alt text-gray-400 w-4 mr-1"></i> 
                                            {{ Str::limit($item->lokasi, 20) }}
                                        </span>
                                        <span class="flex items-center text-xs text-gray-500" title="Mitra">
                                            <i class="fas fa-handshake text-gray-400 w-4 mr-1"></i> 
                                            {{ Str::limit($item->mitra, 20) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="font-medium">{{ $item->tahun_akademik }}</div>
                                    <span class="text-xs text-gray-400">{{ ucfirst($item->semester) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        {{-- Badge Verifikasi --}}
                                        <div>
                                            @if($item->status_verifikasi === 'verified')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1 mt-0.5"></i> Verified
                                                </span>
                                            @elseif($item->status_verifikasi === 'rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1 mt-0.5"></i> Rejected
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1 mt-0.5"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Badge Pelaksanaan --}}
                                        <div class="text-xs text-gray-500 pl-1">
                                            Status: <span class="font-medium">{{ ucfirst($item->status) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('pengmas.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                    @if ($item->status_verifikasi !== 'verified' && auth()->user()->canInputTriDharma() && $item->user_id === auth()->id())
                                        <a href="{{ route('pengmas.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <button type="button" onclick="document.getElementById('delete-form-{{ $item->id }}').submit()" class="text-red-600 hover:text-red-900">Hapus</button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('pengmas.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $pengmas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>