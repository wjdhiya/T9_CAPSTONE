<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Penelitian') }}
            </h2>
            @if(auth()->user()->canInputTriDharma())
                <a href="{{ route('penelitian.create') }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition" style="background-color: #a02127;">
                    Tambah Penelitian
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('penelitian.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            {{-- Placeholder diperbarui untuk indikasi pencarian dosen --}}
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul penelitian atau Nama Dosen..." class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                            <select name="tahun_akademik" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Semua Tahun Akademik</option>
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ request('tahun_akademik') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Akademik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verifikasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($penelitian as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($item->judul, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucwords(str_replace('_', ' ', $item->jenis)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->tahun_akademik }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 text-xs font-semibold rounded-full 
                                            {{ $item->status === 'selesai'
                                                ? 'bg-green-100 text-green-800'
                                                : ($item->status === 'berjalan'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 text-xs font-semibold rounded-full 
                                            @if($item->status_verifikasi === 'disetujui') bg-green-100 text-green-800
                                            @elseif($item->status_verifikasi === 'ditolak') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{-- PERBAIKAN: Mengubah "Pending" menjadi "Menunggu" --}}
                                            {{ $item->status_verifikasi === 'pending' ? 'Menunggu' : ucfirst($item->status_verifikasi) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('penelitian.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                        @if ($item->status_verifikasi !== 'disetujui' && auth()->user()->canInputTriDharma() && $item->user_id === auth()->id())
                                            <a href="{{ route('penelitian.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <button type="button" onclick="document.getElementById('delete-form-{{ $item->id }}').submit()" class="text-red-600 hover:text-red-900">Hapus</button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('penelitian.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data penelitian.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $penelitian->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>