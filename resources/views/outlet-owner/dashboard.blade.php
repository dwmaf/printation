@extends('layouts.app')

@section('child')
    <div class="min-h-screen bg-gray-100 p-8">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Dashboard {{ $outlet->name }}</h1>
                <p class="text-gray-500">Selamat datang, {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                    Paket: {{ $outlet->max_stations }} Station
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI untuk statisktik --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card Pendapatan -->
                    <div class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-green-500">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
                        <h3 class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>

                    <!-- Card Antrian -->
                    <a href="{{ route('outlet.payments') }}" class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-yellow-500 hover:bg-yellow-50 transition block">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Antrian Bayar</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $pendingCount }}</h3>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded">VERIFIKASI SEKARANG →</span>
                        </div>
                    </a>
                </div>

                {{-- Placeholder untuk Chart atau Data Lain di masa depan --}}
                <div class="bg-white p-12 rounded-2xl shadow-lg border border-gray-100 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-800">Modul Statistik Segera Hadir</h3>
                    <p class="text-gray-400 text-sm">Pantau performa harian outlet Anda di sini.</p>
                </div>
            </div>

            {{-- KOLOM KANAN: PENGATURAN OUTLET / QRIS (NEW) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <h2 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        QRIS Pembayaran
                    </h2>
                    
                    <div class="flex flex-col items-center p-4 bg-gray-50 rounded-xl mb-4 border border-gray-100">
                        @if($outlet->qris_path)
                            <img src="{{ asset('storage/' . $outlet->qris_path) }}" class="h-32 w-auto object-contain rounded shadow-sm mb-3">
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">QRIS AKTIF</span>
                        @else
                            <div class="h-32 w-32 flex items-center justify-center text-gray-300 border-2 border-dashed border-gray-200 rounded-lg mb-3">
                                No QRIS
                            </div>
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">BELUM DISET</span>
                        @endif
                    </div>

                    <a href="{{ route('outlet.editQRIS') }}" class="block w-full text-center bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl transition shadow-lg">
                        GANTI QRIS
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
