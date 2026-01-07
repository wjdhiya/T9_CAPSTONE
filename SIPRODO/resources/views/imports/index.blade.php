<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bulk Import Tri Dharma
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <p class="text-gray-700">
                        Format file didukung: CSV / XLSX.
                    </p>
                    <p class="text-gray-700 mt-2">
                        Kolom minimal: nip, judul_penelitian/judul_publikasi/judul_pkm, tahun, semester.
                    </p>
                    <p class="text-gray-700 mt-2">
                        Untuk single-file (campuran), gunakan kolom kategori/jenis_data (penelitian|publikasi|pengmas)
                        atau sistem akan auto-detect dari field.
                    </p>
                    <p class="text-gray-700 mt-2">
                        Upsert key:
                        Penelitian (user_id+judul_penelitian+tahun+semester),
                        Publikasi (user_id+judul_publikasi+jenis+tahun+semester),
                        Pengmas (user_id+judul_pkm+tahun+semester).
                    </p>
                    <p class="text-gray-700 mt-2">
                        Catatan: jika data existing sudah terverifikasi, baris tersebut akan dilewati.
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-4">Import Semua (Auto Detect)</h3>
                    <form method="POST" action="{{ route('imports.tridharma.auto') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="w-full rounded-md border-gray-300" required>
                        @error('file')
                            <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                        <button type="submit"
                            class="mt-4 w-full px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900">Import</button>
                    </form>
                </div>
            </div>

            @if(session('import_errors'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg mb-4">Detail Error</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Row</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Message
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach(session('import_errors') as $err)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $err['row'] ?? '-' }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $err['message'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>