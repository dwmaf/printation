@extends('layouts.app')

@section('child')
    <div class="flex p-6 gap-4 min-h-screen">
        <div class="bg-[#FAFAFA] rounded-xl flex flex-col">
            <div class="flex justify-center items-center mb-8 p-6">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-14 h-14">
                <h1 class="font-koulen text-4xl">Printation</h1>
            </div>
            <h2 class="font-bold text-[#8E8D8D] mb-2 ml-4">Menu</h2>
            <div class="flex flex-col">
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24"><path fill="currentColor" d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z"/></svg>Dashboard</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a9.97 9.97 0 0 0 1.3 4.935l-1.249 3.749a1 1 0 0 0 1.265 1.265l3.749-1.25A9.96 9.96 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2m0 6c-.902 0-1.731.297-2.4.8a1 1 0 1 1-1.2-1.6a6 6 0 0 1 8.4 8.4a1 1 0 0 1-1.598-1.2A4 4 0 0 0 12 8m-5 3a1 1 0 0 1 1 1a4 4 0 0 0 4 4a1 1 0 1 1 0 2a6 6 0 0 1-6-6a1 1 0 0 1 1-1m5-1a2 2 0 1 0 0 4a2 2 0 0 0 0-4" clip-rule="evenodd"/></svg>Payments</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 512 512"><path fill="currentColor" d="M16 352a48.05 48.05 0 0 0 48 48h133.88l-4 32H144a16 16 0 0 0 0 32h224a16 16 0 0 0 0-32h-49.88l-4-32H448a48.05 48.05 0 0 0 48-48v-48H16Zm240-16a16 16 0 1 1-16 16a16 16 0 0 1 16-16M496 96a48.05 48.05 0 0 0-48-48H64a48.05 48.05 0 0 0-48 48v192h480Z"/></svg>Stations</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a9.97 9.97 0 0 0 1.3 4.935l-1.249 3.749a1 1 0 0 0 1.265 1.265l3.749-1.25A9.96 9.96 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2m0 6c-.902 0-1.731.297-2.4.8a1 1 0 1 1-1.2-1.6a6 6 0 0 1 8.4 8.4a1 1 0 0 1-1.598-1.2A4 4 0 0 0 12 8m-5 3a1 1 0 0 1 1 1a4 4 0 0 0 4 4a1 1 0 1 1 0 2a6 6 0 0 1-6-6a1 1 0 0 1 1-1m5-1a2 2 0 1 0 0 4a2 2 0 0 0 0-4" clip-rule="evenodd"/></svg>Files</ul>
                
            </div>

            <div class="mt-auto text-[#B1B0AB] w-full h-17 pr-4 pl-6 flex hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors cursor-pointer rounded-b-xl hover:rounded-b-xl gap-3 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 14 14"><path fill="currentColor" fill-rule="evenodd" d="M2.5.351a40.5 40.5 0 0 1 5.74 0c1.136.081 2.072.874 2.264 1.932a2.25 2.25 0 0 0-2.108 2.28H4.754a2.25 2.25 0 0 0 0 4.5h3.642a2.25 2.25 0 0 0 2.145 2.281l-.004.085c-.06 1.2-1.06 2.132-2.296 2.22a40.5 40.5 0 0 1-5.742 0C1.263 13.561.263 12.63.203 11.43a91 91 0 0 1 0-8.859C.263 1.372 1.263.439 2.5.351m7.356 5.462L9.661 4.7a1 1 0 0 1 1.432-1.067c1.107.553 2.178 1.624 2.731 2.731a1 1 0 0 1 0 .895c-.553 1.107-1.624 2.178-2.731 2.731A1 1 0 0 1 9.66 8.924l.195-1.111H4.754a1 1 0 1 1 0-2z" clip-rule="evenodd"/></svg>
                Logout
            </div>
        </div>

        <div class="h-full flex-1 bg-gray-100 p-8">
    
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
    </div>
@endsection
