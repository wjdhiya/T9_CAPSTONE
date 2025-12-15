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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('penelitian.index') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #fee2e2;"
                               onmouseover="this.style.backgroundColor='#fecaca'"
                               onmouseout="this.style.backgroundColor='#fee2e2'">
                                <svg class="w-6 h-6 mr-3" style="color: #a02127;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium" style="color: #a02127;">Lihat Data Penelitian</span>
                            </a>

                            <a href="{{ route('penelitian.create') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #ffffff; border: 1px solid #fee2e2;"
                               onmouseover="this.style.backgroundColor='#fff7f7'"
                               onmouseout="this.style.backgroundColor='#ffffff'">
                                <svg class="w-6 h-6 mr-3" style="color: #a02127;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="font-medium" style="color: #a02127;">Tambah Penelitian</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('publikasi.index') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #d1fae5;"
                               onmouseover="this.style.backgroundColor='#a7f3d0'"
                               onmouseout="this.style.backgroundColor='#d1fae5'">
                                <svg class="w-6 h-6 mr-3" style="color: #10784b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
                                </svg>
                                <span class="font-medium" style="color: #10784b;">Lihat Data Publikasi</span>
                            </a>

                            <a href="{{ route('publikasi.create') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #ffffff; border: 1px solid #d1fae5;"
                               onmouseover="this.style.backgroundColor='#f7fff9'"
                               onmouseout="this.style.backgroundColor='#ffffff'">
                                <svg class="w-6 h-6 mr-3" style="color: #10784b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="font-medium" style="color: #10784b;">Tambah Publikasi</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('pengmas.index') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #f3f4f6;"
                               onmouseover="this.style.backgroundColor='#e5e7eb'"
                               onmouseout="this.style.backgroundColor='#f3f4f6'">
                                <svg class="w-6 h-6 mr-3" style="color: #585858;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium" style="color: #585858;">Lihat Data Pengabdian</span>
                            </a>

                            <a href="{{ route('pengmas.create') }}"
                               class="flex items-center p-4 rounded-lg transition"
                               style="background-color: #ffffff; border: 1px solid #f3f4f6;"
                               onmouseover="this.style.backgroundColor='#fbfbfb'"
                               onmouseout="this.style.backgroundColor='#ffffff'">
                                <svg class="w-6 h-6 mr-3" style="color: #585858;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="font-medium" style="color: #585858;">Tambah Pengabdian Masyarakat</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($isSuperAdmin ?? false)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">
                            Dosen Paling Aktif Semester {{ $currentSemester }} {{ $currentYear }}
                        </h3>
                        <div class="h-64">
                            <canvas id="topLecturersChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">
                            Antrian Verifikasi
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('penelitian.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Penelitian</span>
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        {{ $verificationQueue['penelitian'] ?? 0 }} Menunggu
                                    </span>
                                </div>
                            </a>
                            <a href="{{ route('publikasi.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Publikasi</span>
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        {{ $verificationQueue['publikasi'] ?? 0 }} Menunggu
                                    </span>
                                </div>
                            </a>
                            <a href="{{ route('pengmas.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Pengabdian Masyarakat</span>
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        {{ $verificationQueue['pengmas'] ?? 0 }} Menunggu
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">
                                Ekspor Data Tridharma
                            </h3>
                            <form action="{{ route('reports.export.excel') }}" method="GET" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Data</label>
                                    <select name="jenis" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <option value="penelitian">Penelitian</option>
                                        <option value="publikasi">Publikasi</option>
                                        <option value="pengmas">Pengabdian Masyarakat</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                        <select name="semester" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            <option value="1">Ganjil (Jan-Jun)</option>
                                            <option value="2" {{ $currentSemester == 2 ? 'selected' : '' }}>Genap (Jul-Des)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                        <input type="number" name="tahun_akademik" value="{{ $currentYear }}" min="2020" max="{{ date('Y') + 1 }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white" style="background-color: #a02127;" onmouseover="this.style.backgroundColor='#8c1d22'" onmouseout="this.style.backgroundColor='#a02127'">
                                    Ekspor ke Excel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isSuperAdmin ?? false)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('topLecturersChart').getContext('2d');
                    const lecturers = @json($topLecturers);
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: lecturers.map(lecturer => lecturer.name),
                            datasets: [
                                {
                                    label: 'Penelitian',
                                    data: lecturers.map(lecturer => lecturer.total_penelitian || 0),
                                    backgroundColor: 'rgba(220, 38, 38, 0.8)',
                                    borderColor: 'rgba(220, 38, 38, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Publikasi',
                                    data: lecturers.map(lecturer => lecturer.total_publikasi || 0),
                                    backgroundColor: 'rgba(16, 120, 75, 0.8)',
                                    borderColor: 'rgba(16, 120, 75, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Pengabdian',
                                    data: lecturers.map(lecturer => lecturer.total_pengmas || 0),
                                    backgroundColor: 'rgba(30, 64, 175, 0.8)',
                                    borderColor: 'rgba(30, 64, 175, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${context.raw} kegiatan`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endif
</x-app-layout>