<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- PERBAIKAN: Hanya menampilkan nama halaman utama, menghapus akses $publikasi->judul --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Publikasi</h2>
            @if(auth()->user()->isDosen())
                <a href="{{ route('publikasi.create') }}" class="px-4 py-2 text-white rounded-lg" style="background-color: #10784b;">Tambah Publikasi</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="rounded-md border-gray-300">
                    <select name="jenis" class="rounded-md border-gray-300">
                        <option value="">Semua Jenis</option>
                        <option value="jurnal" {{ request('jenis') == 'jurnal' ? 'selected' : '' }}>Jurnal</option>
                        <option value="prosiding" {{ request('jenis') == 'prosiding' ? 'selected' : '' }}>Prosiding</option>
                        <option value="buku" {{ request('jenis') == 'buku' ? 'selected' : '' }}>Buku</option>
                        {{-- Tambahkan jenis lain jika diperlukan --}}
                    </select>
                    <select name="indexing" class="rounded-md border-gray-300">
                        <option value="">Semua Indexing</option>
                        <option value="scopus" {{ request('indexing') == 'scopus' ? 'selected' : '' }}>Scopus</option>
                        <option value="wos" {{ request('indexing') == 'wos' ? 'selected' : '' }}>Web of Science</option>
                        <option value="sinta" {{ request('indexing') == 'sinta' ? 'selected' : '' }}>SINTA</option>
                        {{-- Tambahkan filter SINTA 1-6 jika diperlukan --}}
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Filter</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indexing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Akademik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($publikasi as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($item->judul, 40) }}</div>
                                {{-- Tampilkan Dosen Pengirim untuk Admin/Kaprodi --}}
                                @if(auth()->user()->canVerify())
                                    <span class="text-xs text-gray-500 block">Oleh: {{ $item->user->name ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($item->penulis, 30) }}</td>
                            <td class="px-6 py-4"><span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($item->jenis) }}</span></td>
                            <td class="px-6 py-4 text-sm">{{ $item->indexing ? strtoupper($item->indexing) : '-' }}</td>
                            {{-- Menggunakan $item->tahun_akademik yang ada di Controller Anda --}}
                            <td class="px-6 py-4 text-sm">{{ $item->tahun_akademik }}</td> 
                            <td class="px-6 py-4">
                                <span class="px-2 text-xs font-semibold rounded-full 
                                    @if($item->status_verifikasi === 'verified') bg-green-100 text-green-800
                                    @elseif($item->status_verifikasi === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($item->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                
                                {{-- Tombol Lihat (Selalu Ada) --}}
                                <a href="{{ route('publikasi.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>

                                {{-- Aksi untuk Dosen Pemilik (Edit & Delete) --}}
                                @if(auth()->user()->isDosen() && $item->user_id === auth()->id())
                                    {{-- Izinkan Edit/Delete jika status masih pending --}}
                                    @if($item->status_verifikasi === 'pending')
                                        <a href="{{ route('publikasi.edit', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        
                                        {{-- Tambahkan Form Delete --}}
                                        <form action="{{ route('publikasi.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus publikasi ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Aksi untuk Kaprodi/Super Admin (Verifikasi) --}}
                                @if(auth()->user()->canVerify())
                                    {{-- Tampilkan tombol verifikasi jika status pending --}}
                                    @if ($item->status_verifikasi === 'pending')
                                        <a href="{{ route('publikasi.show', $item) }}" class="text-green-600 hover:text-green-900 ml-3">Verifikasi</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data publikasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $publikasi->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>