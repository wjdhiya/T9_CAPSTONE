<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2" style="color: #a02127;">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-gray-600">
                        @if(auth()->user()->isAdmin())
                            Anda login sebagai <span class="font-semibold">Admin</span>
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
                                <p class="text-3xl font-bold" style="color: #a02127;"><span
                                        id="stat-penelitian-total">{{ $stats['penelitian']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #fee2e2;">
                                <svg class="w-8 h-8" style="color: #a02127;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold"
                                id="stat-penelitian-verified">{{ $stats['penelitian']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('penelitian.index') }}"
                                class="relative flex flex-col sm:flex-row items-center p-4 rounded-lg bg-red-50 hover:bg-red-100 transition-colors border border-red-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-red-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium text-red-700 text-center">Lihat Data</span>
                                @if(($isKaprodi ?? false) && (($kaprodiNotifications['penelitian'] ?? 0) > 0))
                                    <span
                                        class="absolute -top-2 -right-2 min-w-[1.25rem] h-5 px-1 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center">
                                        {{ $kaprodiNotifications['penelitian'] }}
                                    </span>
                                @endif
                            </a>
                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('penelitian.create') }}"
                                    class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-red-50 transition-colors border border-red-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-red-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-red-700 text-center">Tambah Data</span>
                                </a>
                            @endif
                        </div>

                        @if($isKaprodi ?? false)
                            <div class="mt-4 border-t pt-4">
                                <form id="penelitianImportForm" method="POST"
                                    action="{{ route('imports.tridharma', ['type' => 'penelitian']) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="penelitianDropZone"
                                        class="relative w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group cursor-pointer">

                                        <input type="file" id="penelitianImportFile" name="file" accept=".csv, .xlsx, .xls"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <div id="penelitianContent"
                                            class="flex flex-col items-center justify-center h-full pt-5 pb-6 text-center pointer-events-none">
                                            <i
                                                class="fas fa-file-upload text-gray-400 text-2xl mb-2 group-hover:text-blue-500 transition-colors"></i>
                                            <p class="text-sm text-gray-500"><span
                                                    class="font-semibold text-blue-600">Klik</span> atau drag file CSV/XLSX
                                            </p>
                                        </div>

                                        <div id="penelitianPreview"
                                            class="hidden absolute inset-0 w-full h-full bg-green-50 rounded-lg flex flex-col items-center justify-center z-20">
                                            <i class="fas fa-file-csv text-green-600 text-2xl mb-1"></i>
                                            <p id="penelitianFilename"
                                                class="text-sm font-medium text-gray-900 truncate w-3/4 text-center px-2">
                                            </p>
                                            <button type="button" id="penelitianRemoveBtn"
                                                class="mt-2 text-xs text-red-600 hover:text-red-800 underline font-semibold cursor-pointer relative z-30">
                                                <i class="fas fa-times mr-1"></i> Ganti File
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="button" id="penelitianImportBtn" disabled
                                            class="w-full inline-flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed transition-colors">
                                            Import Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Publikasi</p>
                                <p class="text-3xl font-bold" style="color: #10784b;"><span
                                        id="stat-publikasi-total">{{ $stats['publikasi']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #d1fae5;">
                                <svg class="w-8 h-8" style="color: #10784b;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold"
                                id="stat-publikasi-verified">{{ $stats['publikasi']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('publikasi.index') }}"
                                class="relative flex flex-col sm:flex-row items-center p-4 rounded-lg bg-green-50 hover:bg-green-100 transition-colors border border-green-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-green-700" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7h18M3 12h18M3 17h18"></path>
                                </svg>
                                <span class="font-medium text-green-700 text-center">Lihat Data</span>
                                @if(($isKaprodi ?? false) && (($kaprodiNotifications['publikasi'] ?? 0) > 0))
                                    <span
                                        class="absolute -top-2 -right-2 min-w-[1.25rem] h-5 px-1 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center">
                                        {{ $kaprodiNotifications['publikasi'] }}
                                    </span>
                                @endif
                            </a>
                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('publikasi.create') }}"
                                    class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-green-50 transition-colors border border-green-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-green-700" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-green-700 text-center">Tambah Data</span>
                                </a>
                            @endif
                        </div>

                        @if($isKaprodi ?? false)
                            <div class="mt-4 border-t pt-4">
                                <form id="publikasiImportForm" method="POST"
                                    action="{{ route('imports.tridharma', ['type' => 'publikasi']) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="publikasiDropZone"
                                        class="relative w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group cursor-pointer">
                                        <input type="file" id="publikasiImportFile" name="file" accept=".csv, .xlsx, .xls"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div id="publikasiContent"
                                            class="flex flex-col items-center justify-center h-full pt-5 pb-6 text-center pointer-events-none">
                                            <i
                                                class="fas fa-file-upload text-gray-400 text-2xl mb-2 group-hover:text-blue-500 transition-colors"></i>
                                            <p class="text-sm text-gray-500"><span
                                                    class="font-semibold text-blue-600">Klik</span> atau drag file CSV/XLSX
                                            </p>
                                        </div>
                                        <div id="publikasiPreview"
                                            class="hidden absolute inset-0 w-full h-full bg-green-50 rounded-lg flex flex-col items-center justify-center z-20">
                                            <i class="fas fa-file-csv text-green-600 text-2xl mb-1"></i>
                                            <p id="publikasiFilename"
                                                class="text-sm font-medium text-gray-900 truncate w-3/4 text-center px-2">
                                            </p>
                                            <button type="button" id="publikasiRemoveBtn"
                                                class="mt-2 text-xs text-red-600 hover:text-red-800 underline font-semibold cursor-pointer relative z-30">
                                                <i class="fas fa-times mr-1"></i> Ganti File
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" id="publikasiImportBtn" disabled
                                            class="w-full inline-flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed transition-colors">
                                            Import Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pengabdian</p>
                                <p class="text-3xl font-bold" style="color: #003366;"><span
                                        id="stat-pengmas-total">{{ $stats['pengmas']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #dbeafe;">
                                <svg class="w-8 h-8" style="color: #003366;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold"
                                id="stat-pengmas-verified">{{ $stats['pengmas']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('pengmas.index') }}"
                                class="relative flex flex-col sm:flex-row items-center p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors border border-blue-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3" style="color: #003366;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium text-center" style="color: #003366;">Lihat Data</span>
                                @if(($isKaprodi ?? false) && (($kaprodiNotifications['pengmas'] ?? 0) > 0))
                                    <span
                                        class="absolute -top-2 -right-2 min-w-[1.25rem] h-5 px-1 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center">
                                        {{ $kaprodiNotifications['pengmas'] }}
                                    </span>
                                @endif
                            </a>
                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('pengmas.create') }}"
                                    class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-blue-50 transition-colors border border-blue-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3" style="color: #003366;" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-center" style="color: #003366;">Tambah Data</span>
                                </a>
                            @endif
                        </div>

                        @if($isKaprodi ?? false)
                            <div class="mt-4 border-t pt-4">
                                <form id="pengmasImportForm" method="POST"
                                    action="{{ route('imports.tridharma', ['type' => 'pengmas']) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="pengmasDropZone"
                                        class="relative w-full h-32 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group cursor-pointer">
                                        <input type="file" id="pengmasImportFile" name="file" accept=".csv, .xlsx, .xls"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div id="pengmasContent"
                                            class="flex flex-col items-center justify-center h-full pt-5 pb-6 text-center pointer-events-none">
                                            <i
                                                class="fas fa-file-upload text-gray-400 text-2xl mb-2 group-hover:text-blue-500 transition-colors"></i>
                                            <p class="text-sm text-gray-500"><span
                                                    class="font-semibold text-blue-600">Klik</span> atau drag file CSV/XLSX
                                            </p>
                                        </div>
                                        <div id="pengmasPreview"
                                            class="hidden absolute inset-0 w-full h-full bg-green-50 rounded-lg flex flex-col items-center justify-center z-20">
                                            <i class="fas fa-file-csv text-green-600 text-2xl mb-1"></i>
                                            <p id="pengmasFilename"
                                                class="text-sm font-medium text-gray-900 truncate w-3/4 text-center px-2">
                                            </p>
                                            <button type="button" id="pengmasRemoveBtn"
                                                class="mt-2 text-xs text-red-600 hover:text-red-800 underline font-semibold cursor-pointer relative z-30">
                                                <i class="fas fa-times mr-1"></i> Ganti File
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" id="pengmasImportBtn" disabled
                                            class="w-full inline-flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed transition-colors">
                                            Import Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($isKaprodi ?? false)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-semibold" style="color: #a02127;">Dosen Paling Aktif</h3>
                            <div class="flex gap-2">
                                <select id="kaprodiTahun" class="border-gray-300 rounded-md shadow-sm">
                                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                <select id="kaprodiSemester" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="ganjil" {{ $currentSemester == 1 ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ $currentSemester == 2 ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-48">
                            <canvas id="topLecturersChart"></canvas>
                        </div>
                        <div id="topLecturersList" class="mt-4 border-t pt-4">
                            <p class="text-sm text-gray-500 text-center">Memuat data...</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">Ekspor Data Tridharma</h3>
                        <form action="{{ route('reports.export.excel') }}" method="GET" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Data</label>
                                <select name="jenis"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <option value="all" selected>Semua Data</option>
                                    <option value="penelitian">Penelitian</option>
                                    <option value="publikasi">Publikasi</option>
                                    <option value="pengmas">Pengabdian Masyarakat</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                                    <select name="semester" id="semesterSelect"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                                        onchange="toggleYearField()">
                                        <option value="">Semua Periode</option>
                                        <option value="1">Ganjil</option>
                                        <option value="2">Genap</option>
                                    </select>
                                </div>
                                <div id="yearField">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                    <input type="number" name="tahun" value="{{ $currentYear }}" min="2022"
                                        max="{{ date('Y') + 1 }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-700 hover:bg-red-800 transition-colors">
                                Ekspor ke Excel
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($isAdmin ?? false)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-xl lg:col-span-1">
                        <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">Antrian Verifikasi</h3>
                        <div class="space-y-4">
                            <a href="{{ route('penelitian.index') }}"
                                class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Penelitian</span>
                                    <span
                                        class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">{{ $verificationQueue['penelitian'] ?? 0 }}
                                        Menunggu</span>
                                </div>
                            </a>
                            <a href="{{ route('publikasi.index') }}"
                                class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Publikasi</span>
                                    <span
                                        class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">{{ $verificationQueue['publikasi'] ?? 0 }}
                                        Menunggu</span>
                                </div>
                            </a>
                            <a href="{{ route('pengmas.index') }}"
                                class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <span>Pengabdian</span>
                                    <span
                                        class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">{{ $verificationQueue['pengmas'] ?? 0 }}
                                        Menunggu</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isKaprodi ?? false)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // ========== 1. CHART & STATS LOGIC ==========
                let kaprodiChart = null;

                function setText(id, value) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = value;
                }

                function toggleYearField() {
                    const semesterSelect = document.getElementById('semesterSelect');
                    const yearField = document.getElementById('yearField');
                    const yearInput = document.querySelector('input[name="tahun"]');

                    if (semesterSelect.value === '') {
                        yearField.style.display = 'none';
                        yearInput.removeAttribute('required');
                        yearInput.disabled = true;
                    } else {
                        yearField.style.display = 'block';
                        yearInput.setAttribute('required', 'required');
                        yearInput.disabled = false;
                    }
                }

                // Initial load
                document.addEventListener('DOMContentLoaded', function () {
                    toggleYearField();

                    // Setup Chart with empty data first
                    const ctx = document.getElementById('topLecturersChart').getContext('2d');

                    kaprodiChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [
                                { label: 'Penelitian', data: [], backgroundColor: 'rgba(220, 38, 38, 0.8)' },
                                { label: 'Publikasi', data: [], backgroundColor: 'rgba(16, 120, 75, 0.8)' },
                                { label: 'Pengabdian', data: [], backgroundColor: 'rgba(30, 64, 175, 0.8)' }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'top' },
                                title: { display: false }
                            },
                            scales: {
                                x: { stacked: false },
                                y: { beginAtZero: true, ticks: { stepSize: 1 } }
                            }
                        }
                    });

                    // Init Upload Widgets
                    initImportWidget('penelitian');
                    initImportWidget('publikasi');
                    initImportWidget('pengmas');

                    // Add change listeners for tahun/semester
                    document.getElementById('kaprodiTahun')?.addEventListener('change', fetchKaprodiSummary);
                    document.getElementById('kaprodiSemester')?.addEventListener('change', fetchKaprodiSummary);

                    // Fetch data immediately based on selected tahun/semester
                    fetchKaprodiSummary();
                    setInterval(fetchKaprodiSummary, 30000); // Poll every 30 seconds
                });

                async function fetchKaprodiSummary() {
                    const tahun = document.getElementById('kaprodiTahun')?.value;
                    const semester = document.getElementById('kaprodiSemester')?.value;
                    if (!tahun || !semester) return;

                    const url = `{{ route('dashboard.kaprodi.summary') }}?tahun=${encodeURIComponent(tahun)}&semester=${encodeURIComponent(semester)}`;
                    try {
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;
                        const data = await res.json();

                        setText('stat-penelitian-total', data?.stats?.penelitian?.total ?? 0);
                        setText('stat-penelitian-verified', data?.stats?.penelitian?.verified ?? 0);
                        setText('stat-publikasi-total', data?.stats?.publikasi?.total ?? 0);
                        setText('stat-publikasi-verified', data?.stats?.publikasi?.verified ?? 0);
                        setText('stat-pengmas-total', data?.stats?.pengmas?.total ?? 0);
                        setText('stat-pengmas-verified', data?.stats?.pengmas?.verified ?? 0);

                        // Update chart
                        if (kaprodiChart) {
                            const l = data?.topLecturers ?? [];
                            kaprodiChart.data.labels = l.map(x => x.name?.split(' ').slice(0, 2).join(' ') || 'N/A');
                            kaprodiChart.data.datasets[0].data = l.map(x => x.total_penelitian || 0);
                            kaprodiChart.data.datasets[1].data = l.map(x => x.total_publikasi || 0);
                            kaprodiChart.data.datasets[2].data = l.map(x => x.total_pengmas || 0);
                            kaprodiChart.update();
                        }

                        // Update list
                        const listEl = document.getElementById('topLecturersList');
                        if (listEl) {
                            const lecturers = data?.topLecturers ?? [];
                            if (lecturers.length === 0) {
                                listEl.innerHTML = '<p class="text-sm text-gray-500 text-center">Tidak ada data dosen aktif pada periode ini.</p>';
                            } else {
                                let html = '<div class="space-y-2">';
                                lecturers.forEach((l, idx) => {
                                    const total = (l.total_penelitian || 0) + (l.total_publikasi || 0) + (l.total_pengmas || 0);
                                    const topCategory = getTopCategory(l);
                                    html += `
                                                                        <div class="flex items-center justify-between p-2 rounded-lg ${idx === 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50'}">
                                                                            <div class="flex items-center gap-3">
                                                                                <span class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold ${idx === 0 ? 'bg-yellow-400 text-white' : 'bg-gray-300 text-gray-700'}">${idx + 1}</span>
                                                                                <div>
                                                                                    <p class="font-medium text-gray-900 text-sm">${l.name || 'N/A'}</p>
                                                                                    <p class="text-xs text-gray-500">NIP: ${l.nip || '-'}</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <p class="font-bold text-gray-900">${total} <span class="text-xs font-normal text-gray-500">kegiatan</span></p>
                                                                                <p class="text-xs ${topCategory.color}">${topCategory.label}</p>
                                                                            </div>
                                                                        </div>
                                                                    `;
                                });
                                html += '</div>';
                                listEl.innerHTML = html;
                            }
                        }
                    } catch (e) { console.error(e); }
                }

                function getTopCategory(lecturer) {
                    const p = lecturer.total_penelitian || 0;
                    const pub = lecturer.total_publikasi || 0;
                    const pm = lecturer.total_pengmas || 0;

                    if (p === 0 && pub === 0 && pm === 0) return { label: '-', color: 'text-gray-400' };

                    if (p >= pub && p >= pm) return { label: `Penelitian (${p})`, color: 'text-red-600' };
                    if (pub >= p && pub >= pm) return { label: `Publikasi (${pub})`, color: 'text-green-600' };
                    return { label: `Pengabdian (${pm})`, color: 'text-blue-600' };
                }

                // ========== 2. DRAG & DROP + CLICK LOGIC (HYBRID) ==========
                function initImportWidget(prefix) {
                    const dropZone = document.getElementById(prefix + 'DropZone');
                    const input = document.getElementById(prefix + 'ImportFile');
                    const content = document.getElementById(prefix + 'Content');
                    const preview = document.getElementById(prefix + 'Preview');
                    const filenameLabel = document.getElementById(prefix + 'Filename');
                    const removeBtn = document.getElementById(prefix + 'RemoveBtn');
                    const importBtn = document.getElementById(prefix + 'ImportBtn');
                    const form = document.getElementById(prefix + 'ImportForm');

                    if (!input || !dropZone) {
                        console.warn('[Import ' + prefix + '] Elements not found');
                        return;
                    }

                    // Store selected file (for drag & drop fallback)
                    let selectedFile = null;

                    // --------------------------------------------------------
                    // A. HANDLE CLICK SELECTION (Native Input)
                    // --------------------------------------------------------
                    input.addEventListener('change', function () {
                        if (this.files && this.files.length > 0) {
                            selectedFile = this.files[0];
                            handleFiles(selectedFile);
                        }
                    });

                    // --------------------------------------------------------
                    // B. HANDLE DRAG & DROP
                    // --------------------------------------------------------
                    // Prevent defaults on BOTH dropZone AND input (input is on top with z-10)
                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    function highlight() {
                        dropZone.classList.add('border-blue-500', 'bg-blue-100');
                        dropZone.classList.remove('border-gray-300', 'bg-gray-50');
                    }

                    function unhighlight() {
                        dropZone.classList.remove('border-blue-500', 'bg-blue-100');
                        dropZone.classList.add('border-gray-300', 'bg-gray-50');
                    }

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        if (!dt || !dt.files || dt.files.length === 0) return;

                        const file = dt.files[0];
                        selectedFile = file;

                        // Try to assign to input (may fail in some browsers)
                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            input.files = dataTransfer.files;
                        } catch (err) {
                            console.log('[Import ' + prefix + '] DataTransfer fallback active');
                        }

                        handleFiles(file);
                    }

                    // Add events to BOTH dropZone and input (since input covers dropZone)
                    [dropZone, input].forEach(el => {
                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            el.addEventListener(eventName, preventDefaults, false);
                        });

                        ['dragenter', 'dragover'].forEach(eventName => {
                            el.addEventListener(eventName, highlight, false);
                        });

                        ['dragleave', 'drop'].forEach(eventName => {
                            el.addEventListener(eventName, unhighlight, false);
                        });

                        el.addEventListener('drop', handleDrop, false);
                    });

                    // --------------------------------------------------------
                    // C. LOGIKA PROSES FILE
                    // --------------------------------------------------------
                    function handleFiles(file) {
                        const ext = file.name.split('.').pop().toLowerCase();

                        if (!['csv', 'xlsx', 'xls'].includes(ext)) {
                            alert('Format file salah! Harap upload CSV atau Excel (.xlsx/.xls)');
                            resetFile();
                            return;
                        }

                        // Update UI
                        content.classList.add('hidden');
                        preview.classList.remove('hidden');
                        preview.classList.add('flex');
                        filenameLabel.textContent = file.name;

                        // Enable Button
                        if (importBtn) {
                            importBtn.disabled = false;
                            importBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            importBtn.classList.add('bg-blue-800', 'hover:bg-blue-900', 'cursor-pointer');
                            importBtn.innerText = 'Import Data Sekarang';
                        }
                    }

                    // --------------------------------------------------------
                    // D. RESET FILE
                    // --------------------------------------------------------
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation(); // Stop agar tidak men-trigger input di bawahnya
                            resetFile();
                        });
                    }

                    function resetFile() {
                        input.value = '';
                        selectedFile = null; // Clear the fallback file too
                        content.classList.remove('hidden');
                        preview.classList.add('hidden');
                        preview.classList.remove('flex');

                        if (importBtn) {
                            importBtn.disabled = true;
                            importBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                            importBtn.classList.remove('bg-blue-800', 'hover:bg-blue-900', 'cursor-pointer');
                            importBtn.innerText = 'Import Data';
                        }
                    }

                    // --------------------------------------------------------
                    // E. AJAX IMPORT SUBMIT
                    // --------------------------------------------------------
                    if (importBtn) {
                        importBtn.addEventListener('click', async function () {
                            // Use selectedFile as fallback if input.files is empty
                            const fileToUpload = (input.files && input.files.length > 0) ? input.files[0] : selectedFile;

                            if (!fileToUpload) {
                                alert('Pilih file terlebih dahulu');
                                return;
                            }

                            const originalText = importBtn.innerText;
                            importBtn.innerText = 'Mengupload...';
                            importBtn.disabled = true;

                            // Build FormData manually to ensure file is included
                            const formData = new FormData();
                            formData.append('_token', form.querySelector('input[name="_token"]').value);
                            formData.append('file', fileToUpload);

                            try {
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    credentials: 'same-origin',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                                const result = await response.json().catch(() => null);

                                if (response.ok && result?.success) {
                                    alert(result.message || 'Import Berhasil!');
                                    window.location.reload();
                                } else {
                                    let errorMsg = result?.message || 'Gagal upload. Cek format file.';
                                    // Show detailed errors if available
                                    if (result?.errors && result.errors.length > 0) {
                                        console.error('Import errors:', result.errors);
                                    }
                                    throw new Error(errorMsg);
                                }
                            } catch (error) {
                                alert(error.message || 'Terjadi kesalahan saat upload.');
                                importBtn.innerText = originalText;
                                importBtn.disabled = false;
                                importBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                                importBtn.classList.add('bg-blue-800', 'hover:bg-blue-900', 'cursor-pointer');
                            }
                        });
                    }

                    console.log('[Import ' + prefix + '] Widget initialized');
                }
            </script>
        @endpush
    @endif
</x-app-layout>