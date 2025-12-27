<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Publikasi</h2>
            <div class="flex space-x-2">
                <a href="{{ route('publikasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-2xl font-bold mb-4" style="color: #10784b;">{{ $publikasi->judul }}</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Penulis</p>
                        <p class="font-semibold">{{ $publikasi->penulis }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis</p>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($publikasi->jenis) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Penerbit</p>
                        <p class="font-semibold">{{ $publikasi->penerbit }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Publikasi</p>
                        <p class="font-semibold">{{ $publikasi->tanggal_publikasi?->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ISSN/ISBN</p>
                        <p class="font-semibold">{{ $publikasi->issn_isbn ?? '-' }}</p>
                    </div>
                </div>

                @if($publikasi->indexing || $publikasi->quartile)
                <div class="grid grid-cols-2 gap-4 mb-4">
                    @if($publikasi->indexing)
                    <div>
                        <p class="text-sm text-gray-600">Indexing</p>
                        <p class="font-semibold">{{ strtoupper($publikasi->indexing) }}</p>
                    </div>
                    @endif
                    @if($publikasi->quartile)
                    <div>
                        <p class="text-sm text-gray-600">Quartile</p>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ $publikasi->quartile }}</span>
                    </div>
                    @endif
                </div>
                @endif

                @if($publikasi->doi)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">DOI</p>
                    <p class="font-semibold">{{ $publikasi->doi }}</p>
                </div>
                @endif

                @if($publikasi->url)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">URL</p>
                    <a href="{{ $publikasi->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $publikasi->url }}</a>
                </div>
                @endif

                @if($publikasi->file_publikasi)
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">File Publikasi</p>
                    @if(auth()->user()->canVerify())
                        <a href="{{ route('publikasi.download.publikasi', $publikasi) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Download {{ preg_replace('/^\d+_/', '', basename($publikasi->file_publikasi)) }}
                        </a>
                    @else
                        <a href="{{ Storage::url($publikasi->file_publikasi) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View File
                        </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Verification -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Status Verifikasi</h4>
                <span class="px-4 py-2 text-sm font-semibold rounded-full 
                    @if($publikasi->status_verifikasi === 'verified') bg-green-100 text-green-800
                    @elseif($publikasi->status_verifikasi === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($publikasi->status_verifikasi) }}
                </span>

                @if($publikasi->catatan_verifikasi)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Catatan Verifikasi</p>
                    <p class="text-gray-800">{{ $publikasi->catatan_verifikasi }}</p>
                </div>
                @endif

                @if(auth()->user()->canVerify() && $publikasi->status_verifikasi === 'pending')
                <form action="{{ route('publikasi.verify', $publikasi) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi</label>
                        <textarea name="catatan_verifikasi" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" name="status_verifikasi" value="verified" class="px-4 py-2 bg-green-600 text-white rounded-lg">Setujui</button>
                        <button type="submit" name="status_verifikasi" value="rejected" class="px-4 py-2 bg-red-600 text-white rounded-lg">Tolak</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

