@extends('layouts.app-dashboard')
@section('title', 'Manajemen File')
@section('child')
    <div class="">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                {{-- <div>
                    <h1 class="text-4xl font-koulen">Manajemen File</h1>
                </div> --}}


            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- <!-- Dropdown Pilih Station --> --}}
            <div class="flex justify-between w-full mb-6">
                <div class="relative w-80">
                    <!-- Hidden input pengganti select -->
                    <input type="hidden" name="station_id" id="stationInput">

                    <!-- Tombol dropdown -->
                    <button id="dropdownBtn"
                        class="w-full bg-blue-100 px-4 py-2 rounded-lg cursor-pointer flex items-center gap-2 hover:bg-blue-200 transition text-blue-600 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24">
                            <path fill="#1447e6"
                                d="M8 22a1 1 0 0 1 0-2h.616l.25-2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4.867l.25 2H16a1 1 0 0 1 0 2zm5.116-4h-2.233l-.25 2h2.733z" />
                        </svg>
                        <span id="dropdownText">Pilih Station</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24"
                            class="ml-auto pointer-events-none">
                            <path fill="#6366f1" d="m12 15.4l-6-6L7.4 8l4.6 4.6L16.6 8L18 9.4z" />
                        </svg>
                    </button>

                    <!-- List option -->
                    <ul id="dropdownList"
                        class="hidden absolute w-full bg-white shadow-lg rounded-lg mt-1 z-10 max-h-60 overflow-y-auto">
                        @foreach ($stations as $station)
                            <li data-value="{{ $station->id }}" data-name="{{ $station->name }}"
                                class="px-6 py-3 hover:bg-indigo-100 cursor-pointer flex flex-col border-b border-gray-100 last:border-0">
                                <span class="font-medium text-sm">{{ $station->name }}</span>
                                <span class="text-xs text-gray-500">({{ $station->printFiles->count() }} file)</span>
                            </li>
                        @endforeach
                    </ul>

                </div>
                <button type="button" id="deleteAllBtn"
                    class="bg-red-100 hover:bg-red-200 text-red-600 font-medium px-4 py-2 rounded-lg transition flex items-center gap-2 uppercase text-sm cursor-pointer h-fit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Bersihkan Semua Station
                </button>
            </div>

            <!-- Modal Konfirmasi -->
            <div id="confirmModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
                <div
                    class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform transition-all pointer-events-auto">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-red-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-gray-900">Konfirmasi Hapus</h3>
                            <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-6">
                        Apakah anda yakin ingin menghapus <strong>semua file</strong> di <strong>semua station</strong>?
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>

                    <div class="flex gap-3">
                        <button type="button" id="cancelBtn"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg transition cursor-pointer">
                            Batal
                        </button>
                        <form action="{{ route('outlet.files.clear-all') }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition cursor-pointer">
                                Ya, Hapus Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Container untuk setiap station -->
            @foreach ($stations as $station)
                <div class="station-container hidden" data-station-id="{{ $station->id }}">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="font-black text-gray-800 uppercase tracking-tight">{{ $station->name }}</h3>
                                <p class="text-xs text-gray-400">{{ $station->printFiles->count() }} file tersimpan</p>
                            </div>

                            @if ($station->printFiles->count() > 0)
                                <button type="button" data-station-id="{{ $station->id }}"
                                    class="clear-station-btn text-red-500 text-xs font-bold uppercase tracking-wider flex items-center bg-red-100 cursor-pointer px-4 py-2 rounded-lg gap-2 hover:bg-red-200 transition">
                                    Kosongkan Station
                                </button>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Nama File</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-20">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($station->printFiles as $file)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 cursor-pointer">
                                                <div class="font-bold text-gray-700 truncate max-w-md">
                                                    {{ $file->original_name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold mt-1">
                                                    {{ $file->pages }} Halaman •
                                                    {{ number_format($file->file_size / 1024, 1) }} KB •
                                                    {{ $file->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center w-20">
                                                <form action="{{ route('outlet.files.destroy', $file->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="bg-gray-100 hover:bg-red-100 text-gray-400 hover:text-red-600 p-2 rounded-lg transition cursor-pointer">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-12 text-center text-gray-300 italic text-sm">
                                                Tidak ada file aktif di station ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Konfirmasi untuk Station -->
                    <div id="stationModal{{ $station->id }}"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
                        <div
                            class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform transition-all pointer-events-auto">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="bg-orange-100 rounded-full p-3">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xl text-gray-900">Kosongkan Station</h3>
                                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-6">
                                Apakah anda yakin ingin menghapus <strong>semua file</strong> di
                                <strong>{{ $station->name }}</strong>?
                            </p>

                            <div class="flex gap-3">
                                <button type="button"
                                    class="cancel-station-btn flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg transition cursor-pointer">
                                    Batal
                                </button>
                                <form action="{{ route('outlet.files.bulk-station', $station->id) }}" method="POST"
                                    class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-lg transition cursor-pointer">
                                        Ya, Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pesan jika belum memilih station -->
            <div id="noSelection" class="bg-white rounded-xl shadow-md p-12 text-center ">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-bold text-gray-400">Pilih Station</h3>
                <p class="text-gray-400 text-sm">Pilih station dari dropdown di atas untuk melihat file-file yang tersimpan
                </p>
            </div>
        </div>
    </div>

    <script>
        // document.getElementById('stationSelect').addEventListener('change', function() {
        //     const selectedStationId = this.value;
        //     const noSelection = document.getElementById('noSelection');
        //     const allContainers = document.querySelectorAll('.station-container');

        //     // Sembunyikan semua container
        //     allContainers.forEach(container => {
        //         container.classList.add('hidden');
        //     });

        //     if (selectedStationId) {
        //         // Tampilkan container yang dipilih
        //         const selectedContainer = document.querySelector(`.station-container[data-station-id="${selectedStationId}"]`);
        //         if (selectedContainer) {
        //             selectedContainer.classList.remove('hidden');
        //             noSelection.classList.add('hidden');
        //         }
        //     } else {
        //         // Tampilkan pesan "Pilih Station"
        //         noSelection.classList.remove('hidden');
        //     }
        // });

        const btn = document.getElementById('dropdownBtn');
        const list = document.getElementById('dropdownList');
        const input = document.getElementById('stationInput');
        const dropdownText = document.getElementById('dropdownText');
        const noSelection = document.getElementById('noSelection');
        const allContainers = document.querySelectorAll('.station-container');

        // Modal elements
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        const confirmModal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('cancelBtn');

        // Open modal
        if (deleteAllBtn) {
            deleteAllBtn.onclick = () => {
                confirmModal.classList.remove('hidden');
            };
        }

        // Close modal
        if (cancelBtn) {
            cancelBtn.onclick = () => {
                confirmModal.classList.add('hidden');
            };
        }

        // Close modal when clicking outside
        if (confirmModal) {
            confirmModal.onclick = (e) => {
                if (e.target === confirmModal) {
                    confirmModal.classList.add('hidden');
                }
            };
        }

        btn.onclick = () => list.classList.toggle('hidden');

        document.querySelectorAll('#dropdownList li').forEach(item => {
            item.onclick = () => {
                // Show all items first
                document.querySelectorAll('#dropdownList li').forEach(li => {
                    li.classList.remove('hidden');
                });

                // Hide selected item
                item.classList.add('hidden');

                // Update text dengan nama station
                dropdownText.textContent = item.dataset.name;

                // Set value
                input.value = item.dataset.value;

                // Tutup dropdown
                list.classList.add('hidden');

                // Sembunyikan semua container
                allContainers.forEach(container => {
                    container.classList.add('hidden');
                });

                // Tampilkan container yang dipilih
                const selectedContainer = document.querySelector(
                    `.station-container[data-station-id="${item.dataset.value}"]`);
                if (selectedContainer) {
                    selectedContainer.classList.remove('hidden');
                    noSelection.classList.add('hidden');
                }
            };
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!btn.contains(event.target) && !list.contains(event.target)) {
                list.classList.add('hidden');
            }
        });

        // Station modal handlers
        document.querySelectorAll('.clear-station-btn').forEach(button => {
            button.onclick = () => {
                const stationId = button.getAttribute('data-station-id');
                const modal = document.getElementById('stationModal' + stationId);
                if (modal) {
                    modal.classList.remove('hidden');
                }
            };
        });

        document.querySelectorAll('.cancel-station-btn').forEach(button => {
            button.onclick = () => {
                const modal = button.closest('[id^="stationModal"]');
                if (modal) {
                    modal.classList.add('hidden');
                }
            };
        });

        // Close station modal when clicking outside
        document.querySelectorAll('[id^="stationModal"]').forEach(modal => {
            modal.onclick = (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            };
        });
    </script>
@endsection
