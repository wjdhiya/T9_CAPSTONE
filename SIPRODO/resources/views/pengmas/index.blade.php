<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pengabdian Masyarakat</h2>
            <a href="{{ route('pengmas.create') }}" class="px-4 py-2 text-white rounded-lg" style="background-color: #585858;">Tambah Pengmas</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <!-- Filters -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="rounded-md border-gray-300">
                    <select name="tahun_akademik" class="rounded-md border-gray-300">
                        <option value="">Semua Tahun Akademik</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ request('tahun_akademik') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="">Semua Status</option>
                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Filter</button>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Akademik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengmas as $item)
                        <tr>
                            <td class="px-6 py-4"><div class="text-sm font-medium text-gray-900">{{ Str::limit($item->judul, 40) }}</div></td>
                            <td class="px-6 py-4 text-sm">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($item->lokasi, 30) }}</td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($item->mitra, 30) }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tahun_akademik }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 text-xs font-semibold rounded-full 
                                    @if($item->status_verifikasi === 'verified') bg-green-100 text-green-800
                                    @elseif($item->status_verifikasi === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($item->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('pengmas.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                @if ($item->status_verifikasi !== 'verified' && auth()->user()->isDosen() && $item->user_id === auth()->id())
                                    <a href="{{ route('pengmas.edit', $item) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengabdian masyarakat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $pengmas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

