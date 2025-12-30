@php
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Penelitian') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('penelitian.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Main Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4" style="color: #a02127;">{{ $penelitian->judul }}</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Dosen</p>
                            <p class="font-semibold">{{ $penelitian->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jenis Penelitian</p>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $penelitian->jenis)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Tahun Akademik</p>
                            <p class="font-semibold">{{ $penelitian->tahun_akademik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Semester</p>
                            <p class="font-semibold">{{ ucfirst($penelitian->semester) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @if($penelitian->status === 'selesai') bg-green-100 text-green-800
                                @elseif($penelitian->status === 'berjalan') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($penelitian->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Abstrak</p>
                        <p class="text-gray-800">{{ $penelitian->abstrak }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Mulai</p>
                            <p class="font-semibold">{{ $penelitian->tanggal_mulai?->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Selesai</p>
                            <p class="font-semibold">{{ $penelitian->tanggal_selesai?->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($penelitian->dana || $penelitian->sumber_dana)
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        @if($penelitian->dana)
                        <div>
                            <p class="text-sm text-gray-600">Dana</p>
                            <p class="font-semibold">Rp {{ number_format($penelitian->dana, 0, ',', '.') }}</p>
                        </div>
                        @endif
                        @if($penelitian->sumber_dana)
                        <div>
                            <p class="text-sm text-gray-600">Sumber Dana</p>
                            <p class="font-semibold">{{ $penelitian->sumber_dana }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($penelitian->catatan)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Catatan</p>
                        <p class="text-gray-800">{{ $penelitian->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Files -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">Dokumen</h4>
                    <div class="grid grid-cols-2 gap-4">
                        @if($penelitian->file_proposal)
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2">File Proposal</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('penelitian.download.proposal', $penelitian) }}" class="text-blue-600 hover:underline flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($penelitian->file_proposal)), 30, '...') }}
                                </a>
                            @else
                                <a href="{{ Storage::url($penelitian->file_proposal) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View File
                                </a>
                            @endif
                        </div>
                        @endif
                        @if($penelitian->file_laporan)
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2">File Laporan</p>
                            @if(auth()->user()->canReviewTriDharma())
                                <a href="{{ route('penelitian.download.laporan', $penelitian) }}" class="text-blue-600 hover:underline flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download {{ Str::limit(preg_replace('/^\d+_/', '', basename($penelitian->file_laporan)), 30, '...') }}
                                </a>
                            @else
                                <a href="{{ Storage::url($penelitian->file_laporan) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
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
            </div>

            <!-- Verification Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">Status Verifikasi</h4>
                    <div class="mb-4">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full 
                            @if($penelitian->status_verifikasi === 'verified') bg-green-100 text-green-800
                            @elseif($penelitian->status_verifikasi === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($penelitian->status_verifikasi) }}
                        </span>
                    </div>

                    @if($penelitian->verified_by)
                    <div class="mb-2">
                        <p class="text-sm text-gray-600">Diverifikasi oleh</p>
                        <p class="font-semibold">{{ $penelitian->verifiedBy->name }}</p>
                        <p class="text-sm text-gray-500">{{ $penelitian->verified_at?->format('d M Y H:i') }}</p>
                    </div>
                    @endif

                    @if($penelitian->catatan_verifikasi)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Catatan Verifikasi</p>
                        <p class="text-gray-800">{{ $penelitian->catatan_verifikasi }}</p>
                    </div>
                    @endif

                    @if(auth()->user()->canVerify() && $penelitian->status_verifikasi === 'pending')
                    <form action="{{ route('penelitian.verify', $penelitian) }}" method="POST" class="mt-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi</label>
                            <textarea name="catatan_verifikasi" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" name="status_verifikasi" value="verified" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Setujui
                            </button>
                            <button type="submit" name="status_verifikasi" value="rejected" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Tolak
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

