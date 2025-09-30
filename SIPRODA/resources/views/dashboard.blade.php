<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2" style="color: #a02127;">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-gray-600">
                        @if(auth()->user()->isSuperAdmin())
                            Anda login sebagai <span class="font-semibold">Super Administrator</span>
                        @elseif(auth()->user()->isKaprodi())
                            Anda login sebagai <span class="font-semibold">Kepala Program Studi</span>
                        @else
                            Anda login sebagai <span class="font-semibold">Dosen</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Penelitian Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Penelitian</p>
                                <p class="text-3xl font-bold" style="color: #a02127;">{{ $stats['penelitian']['total'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #fee2e2;">
                                <svg class="w-8 h-8" style="color: #a02127;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-semibold">{{ $stats['penelitian']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- Publikasi Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Publikasi</p>
                                <p class="text-3xl font-bold" style="color: #10784b;">{{ $stats['publikasi']['total'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #d1fae5;">
                                <svg class="w-8 h-8" style="color: #10784b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-semibold">{{ $stats['publikasi']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- Pengmas Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pengabdian Masyarakat</p>
                                <p class="text-3xl font-bold" style="color: #585858;">{{ $stats['pengmas']['total'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #f3f4f6;">
                                <svg class="w-8 h-8" style="color: #585858;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-semibold">{{ $stats['pengmas']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('penelitian.create') }}" class="flex items-center p-4 rounded-lg transition" style="background-color: #fee2e2;" onmouseover="this.style.backgroundColor='#fecaca'" onmouseout="this.style.backgroundColor='#fee2e2'">
                            <svg class="w-6 h-6 mr-3" style="color: #a02127;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium" style="color: #a02127;">Tambah Penelitian</span>
                        </a>
                        <a href="{{ route('publikasi.create') }}" class="flex items-center p-4 rounded-lg transition" style="background-color: #d1fae5;" onmouseover="this.style.backgroundColor='#a7f3d0'" onmouseout="this.style.backgroundColor='#d1fae5'">
                            <svg class="w-6 h-6 mr-3" style="color: #10784b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium" style="color: #10784b;">Tambah Publikasi</span>
                        </a>
                        <a href="{{ route('pengmas.create') }}" class="flex items-center p-4 rounded-lg transition" style="background-color: #f3f4f6;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='#f3f4f6'">
                            <svg class="w-6 h-6 mr-3" style="color: #585858;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium" style="color: #585858;">Tambah Pengabdian Masyarakat</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
