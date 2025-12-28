<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengabdian Masyarakat</h2>
            <div class="flex space-x-2">
                <a href="{{ route('pengmas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-2xl font-bold mb-4" style="color: #585858;">{{ $pengabdianMasyarakat->judul }}</h3>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                    <p class="text-gray-800">{{ $pengabdianMasyarakat->deskripsi }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->lokasi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mitra</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->mitra }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tahun Akademik</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->tahun_akademik }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Semester</p>
                        <p class="font-semibold">{{ ucfirst($pengabdianMasyarakat->semester) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Peserta</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->jumlah_peserta ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Mulai</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->tanggal_mulai?->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Selesai</p>
                        <p class="font-semibold">{{ $pengabdianMasyarakat->tanggal_selesai?->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($pengabdianMasyarakat->status === 'selesai') bg-green-100 text-green-800
                        @elseif($pengabdianMasyarakat->status === 'berjalan') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($pengabdianMasyarakat->status) }}
                    </span>
                </div>

                @if($pengabdianMasyarakat->file_proposal || $pengabdianMasyarakat->file_laporan || $pengabdianMasyarakat->file_dokumentasi)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-3">Dokumen</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($pengabdianMasyarakat->file_proposal)
                            <div class="border rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-2">File Proposal</p>
                                @if(auth()->user()->canVerify())
                                    <a href="{{ route('pengmas.download.proposal', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Download File
                                    </a>
                                @else
                                    <a href="{{ Storage::url($pengabdianMasyarakat->file_proposal) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View File
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if($pengabdianMasyarakat->file_laporan)
                            <div class="border rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-2">File Laporan</p>
                                @if(auth()->user()->canVerify())
                                    <a href="{{ route('pengmas.download.laporan', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Download File
                                    </a>
                                @else
                                    <a href="{{ Storage::url($pengabdianMasyarakat->file_laporan) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View File
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if($pengabdianMasyarakat->file_dokumentasi)
                            <div class="border rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-2">File Dokumentasi</p>
                                @if(auth()->user()->canVerify())
                                    <a href="{{ route('pengmas.download.dokumentasi', $pengabdianMasyarakat) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Download File
                                    </a>
                                @else
                                    <a href="{{ Storage::url($pengabdianMasyarakat->file_dokumentasi) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
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
                </div>
                @endif
            </div>

            <!-- Verification -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Status Verifikasi</h4>
                <span class="px-4 py-2 text-sm font-semibold rounded-full 
                    @if($pengabdianMasyarakat->status_verifikasi === 'verified') bg-green-100 text-green-800
                    @elseif($pengabdianMasyarakat->status_verifikasi === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($pengabdianMasyarakat->status_verifikasi) }}
                </span>

                @if($pengabdianMasyarakat->verified_by)
                <div class="mb-2 mt-4">
                    <p class="text-sm text-gray-600">Diverifikasi oleh</p>
                    <p class="font-semibold">{{ $pengabdianMasyarakat->verifiedBy->name }}</p>
                    <p class="text-sm text-gray-500">{{ $pengabdianMasyarakat->verified_at?->format('d M Y H:i') }}</p>
                </div>
                @endif

                @if($pengabdianMasyarakat->catatan_verifikasi)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Catatan Verifikasi</p>
                    <p class="text-gray-800">{{ $pengabdianMasyarakat->catatan_verifikasi }}</p>
                </div>
                @endif

                @if(auth()->user()->canVerify() && $pengabdianMasyarakat->status_verifikasi === 'pending')
                <form action="{{ route('pengmas.verify', $pengabdianMasyarakat) }}" method="POST" class="mt-6">
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

