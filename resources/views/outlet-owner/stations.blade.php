@extends('layouts.app-dashboard')
@section('title', 'Stations')
@section('child')

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            <button class="text-green-400 hover:text-green-600" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
            <button class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- KOLOM KIRI: DAFTAR STATION --}}
        <div class="lg:col-span-2 bg-white rounded-[20px] shadow-sm border border-gray-100 p-8">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Daftar Perangkat Aktif</h2>

                {{-- Badge Slot Usage --}}
                @php
                    $stationCount = $stations->count();
                    $maxStations = Auth::user()->outlet->max_stations ?? 5;
                    $isFull = $stationCount >= $maxStations;
                @endphp
                <span
                    class="px-3 py-1 rounded-full text-xs font-bold border {{ $isFull ? 'bg-red-50 text-red-600 border-red-200' : 'bg-green-50 text-green-600 border-green-200' }}">
                    {{ $stationCount }} / {{ $maxStations }} Slot Terpakai
                </span>
            </div>

            <div class="space-y-4">
                @forelse($stations as $st)
                    <div
                        class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all">
                        <div class="flex items-center gap-4">
                            {{-- Icon Laptop/PC --}}
                            <div
                                class="w-12 h-12 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $st->name }}</h3>
                                <p class="text-xs text-gray-400 mb-1">{{ $st->email }}</p>

                                {{-- Copy Link Action --}}
                                <button onclick="copyToClipboard('{{ route('upload.page', $st->id) }}')"
                                    class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 flex items-center gap-1 cursor-pointer">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    SALIN LINK UPLOAD
                                </button>
                            </div>
                        </div>

                        {{-- Delete Button --}}
                        <form action="{{ route('outlet.destroyStation', $st->id) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus station ini? Data login akan hilang.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-300 hover:bg-red-50 hover:text-red-500 transition-colors cursor-pointer"
                                title="Hapus Station">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-100 rounded-xl">
                        <p class="text-gray-400 font-medium">Belum ada perangkat terdaftar.</p>
                        <p class="text-xs text-gray-300 mt-1">Tambahkan perangkat baru di kolom sebelah kanan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM KANAN: FORM TAMBAH --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 p-6 sticky top-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Tambah Station</h2>
                    <p class="text-xs text-gray-400 mt-1">Buat akun untuk laptop/komputer printer baru.</p>
                </div>

                <form action="{{ route('outlet.storeStation') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Input Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nama Perangkat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </span>
                            <input type="text" name="name" placeholder="Contoh: PC Depan, Kasir 1"
                                class="w-full bg-gray-50 text-gray-800 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none py-3 pl-10 pr-4 transition-all"
                                required>
                        </div>
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Email Login</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </span>
                            <input type="email" name="email" placeholder="station1@print.com"
                                class="w-full bg-gray-50 text-gray-800 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none py-3 pl-10 pr-4 transition-all"
                                required>
                        </div>
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                            <input type="password" name="password" placeholder="Minimal 4 karakter"
                                class="w-full bg-gray-50 text-gray-800 text-sm rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none py-3 pl-10 pr-4 transition-all"
                                required>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Akun Station
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </div>
    </div>

    {{-- Copy Link --}}
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Link upload berhasil disalin!");
            }).catch(err => {
                console.error('Gagal menyalin text: ', err);
            });
        }
    </script>
@endsection
