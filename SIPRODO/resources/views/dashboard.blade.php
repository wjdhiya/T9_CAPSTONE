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
                                <p class="text-3xl font-bold" style="color: #a02127;"><span id="stat-penelitian-total">{{ $stats['penelitian']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #fee2e2;">
                                <svg class="w-8 h-8" style="color: #a02127;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold" id="stat-penelitian-verified">{{ $stats['penelitian']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('penelitian.index') }}"
                               class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-red-50 hover:bg-red-100 transition-colors border border-red-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium text-red-700 text-center">Lihat Data Penelitian</span>
                            </a>

                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('penelitian.create') }}"
                                   class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-red-50 transition-colors border border-red-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-red-700 text-center">Tambah Penelitian</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Publikasi</p>
                                <p class="text-3xl font-bold" style="color: #10784b;"><span id="stat-publikasi-total">{{ $stats['publikasi']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #d1fae5;">
                                <svg class="w-8 h-8" style="color: #10784b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold" id="stat-publikasi-verified">{{ $stats['publikasi']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('publikasi.index') }}"
                               class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-green-50 hover:bg-green-100 transition-colors border border-green-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
                                </svg>
                                <span class="font-medium text-green-700 text-center">Lihat Data Publikasi</span>
                            </a>

                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('publikasi.create') }}"
                                   class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-green-50 transition-colors border border-green-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-green-700 text-center">Tambah Publikasi</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Pengabdian Masyarakat</p>
                                <p class="text-3xl font-bold" style="color: #003366;"><span id="stat-pengmas-total">{{ $stats['pengmas']['total'] ?? 0 }}</span></p>
                            </div>
                            <div class="p-3 rounded-full" style="background-color: #dbeafe;">
                                <svg class="w-8 h-8" style="color: #003366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-600 font-semibold" id="stat-pengmas-verified">{{ $stats['pengmas']['verified'] ?? 0 }}</span>
                            <span class="text-gray-600 ml-2">Terverifikasi</span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('pengmas.index') }}"
                               class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors border border-blue-200">
                                <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3" style="color: #003366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium text-center" style="color: #003366;">Lihat Data Pengabdian</span>
                            </a>

                            @if(auth()->user()->canInputTriDharma())
                                <a href="{{ route('pengmas.create') }}"
                                   class="flex flex-col sm:flex-row items-center p-4 rounded-lg bg-white hover:bg-blue-50 transition-colors border border-blue-200">
                                    <svg class="w-6 h-6 mb-2 sm:mb-0 sm:mr-3" style="color: #003366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-medium text-center" style="color: #003366;">Tambah Pengabdian Masyarakat</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($isKaprodi ?? false)
                <div class="mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-xl mb-6">
                        <h3 class="text-lg font-semibold mb-2" style="color: #a02127;">Import Data Tri Dharma</h3>
                        <p class="text-sm text-gray-600 mb-4">Upload 1 file (CSV/XLSX). Sistem akan auto-detect kategori dan memecah data otomatis.</p>
                        <p id="kaprodiImportFilename" class="text-sm text-gray-700 mb-4 hidden"></p>
                        <form id="kaprodiImportForm" method="POST" action="{{ route('imports.tridharma.auto') }}" enctype="multipart/form-data">
                            @csrf
                            <input id="kaprodiImportFile" type="file" name="file" accept=".csv,.xlsx,.xls" class="sr-only" onchange="if (window.kaprodiImportSync) window.kaprodiImportSync();">
                            <div class="flex items-center gap-2">
                                <button type="button" id="kaprodiImportBtn" class="flex-1 inline-flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-800 hover:bg-blue-900 transition-colors">
                                    Import File (CSV/XLSX)
                                </button>

                                <button type="button" id="kaprodiImportResetBtn" class="hidden inline-flex items-center justify-center h-10 w-10 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50" aria-label="Reset file">
                                    x
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <h3 class="text-lg font-semibold" style="color: #a02127;">
                                Dosen Paling Aktif
                            </h3>
                            <div class="flex gap-2">
                                <select id="kaprodiTahun" class="border-gray-300 rounded-md shadow-sm">
                                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                <select id="kaprodiSemester" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="ganjil" {{ $currentSemester == 1 ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ $currentSemester == 2 ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="topLecturersChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <div class="pt-0">
                            <h3 class="text-lg font-semibold mb-4" style="color: #a02127;">
                                Ekspor Data Tridharma
                            </h3>

                            <form action="{{ route('reports.export.excel') }}" method="GET" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Data</label>
                                    <select name="jenis" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <option value="all" selected>Semua Data</option>
                                        <option value="penelitian">Penelitian</option>
                                        <option value="publikasi">Publikasi</option>
                                        <option value="pengmas">Pengabdian Masyarakat</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                                        <select name="semester" id="semesterSelect" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" onchange="toggleYearField()">
                                            <option value="">Semua Periode (2022 - {{ date('Y') }})</option>
                                            <option value="1">Ganjil</option>
                                            <option value="2">Genap</option>
                                        </select>
                                    </div>
                                    <div id="yearField">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Akademik</label>
                                        <input type="number" name="tahun" value="{{ $currentYear }}" min="2022" max="{{ date('Y') + 1 }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika ingin semua tahun</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-700 hover:bg-red-800 transition-colors">
                                    Ekspor ke Excel
                                </button>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            @endif

            @if($isAdmin ?? false)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-xl lg:col-span-1">
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
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isKaprodi ?? false)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let kaprodiChart = null;

                function setText(id, value) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = value;
                }

                // Toggle visibility field tahun akademik berdasarkan pilihan semester
                function toggleYearField() {
                    const semesterSelect = document.getElementById('semesterSelect');
                    const yearField = document.getElementById('yearField');
                    const yearInput = document.querySelector('input[name="tahun"]');
                    
                    if (semesterSelect.value === '') {
                        // Semua Periode - sembunyikan field tahun
                        yearField.style.display = 'none';
                        yearInput.removeAttribute('required');
                    } else {
                        // Semester spesifik - tampilkan field tahun
                        yearField.style.display = 'block';
                        yearInput.setAttribute('required', 'required');
                    }
                }
                
                // Set initial state berdasarkan pilihan semester default
                document.addEventListener('DOMContentLoaded', function() {
                    toggleYearField();
                });

                // Chart.js untuk menampilkan grafik dosen paling aktif
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('topLecturersChart').getContext('2d');
                    const lecturers = JSON.parse('{{ json_encode($topLecturers) }}');

                    kaprodiChart = new Chart(ctx, {
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

                async function fetchKaprodiSummary() {
                    const tahun = document.getElementById('kaprodiTahun')?.value;
                    const semester = document.getElementById('kaprodiSemester')?.value;

                    if (!tahun || !semester) return;

                    const url = `{{ route('dashboard.kaprodi.summary') }}?tahun=${encodeURIComponent(tahun)}&semester=${encodeURIComponent(semester)}`;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
                    if (!res.ok) return;

                    const data = await res.json();

                    setText('stat-penelitian-total', data?.stats?.penelitian?.total ?? 0);
                    setText('stat-penelitian-verified', data?.stats?.penelitian?.verified ?? 0);
                    setText('stat-publikasi-total', data?.stats?.publikasi?.total ?? 0);
                    setText('stat-publikasi-verified', data?.stats?.publikasi?.verified ?? 0);
                    setText('stat-pengmas-total', data?.stats?.pengmas?.total ?? 0);
                    setText('stat-pengmas-verified', data?.stats?.pengmas?.verified ?? 0);

                    if (kaprodiChart) {
                        const lecturers = data?.topLecturers ?? [];
                        kaprodiChart.data.labels = lecturers.map(l => l.name);
                        kaprodiChart.data.datasets[0].data = lecturers.map(l => l.total_penelitian || 0);
                        kaprodiChart.data.datasets[1].data = lecturers.map(l => l.total_publikasi || 0);
                        kaprodiChart.data.datasets[2].data = lecturers.map(l => l.total_pengmas || 0);
                        kaprodiChart.update();
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const tahunEl = document.getElementById('kaprodiTahun');
                    const semesterEl = document.getElementById('kaprodiSemester');

                    const importFile = document.getElementById('kaprodiImportFile');
                    const importForm = document.getElementById('kaprodiImportForm');
                    const importBtn = document.getElementById('kaprodiImportBtn');
                    const importFilename = document.getElementById('kaprodiImportFilename');
                    const importResetBtn = document.getElementById('kaprodiImportResetBtn');

                    const defaultBtnText = 'Import File (CSV/XLSX)';

                    window.kaprodiImportSync = function() {
                        if (!importBtn || !importFile) {
                            return;
                        }

                        const hasFile = importFile.files && importFile.files.length > 0;
                        const name = hasFile ? importFile.files[0].name : '';

                        if (importFilename) {
                            if (hasFile) {
                                importFilename.textContent = 'Selected file: ' + name;
                                importFilename.classList.remove('hidden');
                            } else {
                                importFilename.textContent = '';
                                importFilename.classList.add('hidden');
                            }
                        }

                        if (hasFile) {
                            importBtn.textContent = 'Start Import [' + name + ']';
                            importBtn.classList.remove('bg-blue-800', 'hover:bg-blue-900');
                            importBtn.classList.add('bg-green-700', 'hover:bg-green-800');
                            if (importResetBtn) importResetBtn.classList.remove('hidden');
                        } else {
                            importBtn.textContent = defaultBtnText;
                            importBtn.classList.remove('bg-green-700', 'hover:bg-green-800');
                            importBtn.classList.add('bg-blue-800', 'hover:bg-blue-900');
                            if (importResetBtn) importResetBtn.classList.add('hidden');
                        }
                    };

                    if (importBtn && importFile && importForm) {
                        importBtn.addEventListener('click', function() {
                            if (importFile.files && importFile.files.length > 0) {
                                importForm.submit();
                            } else {
                                importFile.click();
                            }
                        });
                    }

                    if (importFile) {
                        importFile.addEventListener('change', function() {
                            if (window.kaprodiImportSync) window.kaprodiImportSync();
                        });
                    }

                    if (importResetBtn && importFile) {
                        importResetBtn.addEventListener('click', function() {
                            importFile.value = '';
                            if (window.kaprodiImportSync) window.kaprodiImportSync();
                        });
                    }

                    if (window.kaprodiImportSync) window.kaprodiImportSync();

                    if (tahunEl) tahunEl.addEventListener('change', fetchKaprodiSummary);
                    if (semesterEl) semesterEl.addEventListener('change', fetchKaprodiSummary);

                    fetchKaprodiSummary();
                    setInterval(fetchKaprodiSummary, 10000);
                });
            </script>
        @endpush
    @endif
</x-app-layout>