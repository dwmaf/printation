<!-- filepath: c:\laragon\www\print-app\resources\views\outlet-owner\stations.blade.php -->
@extends('layouts.app')

@section('child')
<div class="min-h-screen bg-gray-100 p-8">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Manajemen Station</h1>
            <p class="text-gray-500">Kelola akun laptop penerima file</p>
        </div>
        <a href="{{ route('outlet.dashboard') }}" class="text-blue-600 hover:underline font-bold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 mb-6 rounded shadow-sm font-bold">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- KOLOM KIRI: LIST STATION --}}
        <div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-lg text-gray-800">Daftar Station Aktif</h2>
                    <span class="text-sm font-bold px-3 py-1 bg-white border rounded-full {{ $stations->count() >= $outlet->max_stations ? 'text-red-500' : 'text-green-600' }}">
                        {{ $stations->count() }} / {{ $outlet->max_stations }} Slot
                    </span>
                </div>
                
                @if($stations->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach ($stations as $st)
                        <li class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-100 p-3 rounded-lg text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $st->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $st->email }}</div>
                                    <div class="mt-1 flex gap-2">
                                        {{-- Link QR Upload --}}
                                        <a href="{{ route('upload.page', $st->id) }}" target="_blank" class="text-xs text-blue-500 hover:underline">Link Upload Personal</a>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('outlet.destroyStation', $st->id) }}" method="POST" onsubmit="return confirm('Yakin hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-600 hover:bg-red-50 p-2 rounded transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                @else
                    <div class="p-8 text-center text-gray-400">Belum ada station terdaftar.</div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: FORM TAMBAH --}}
        <div>
            <div class="bg-gray-800 rounded-xl shadow-lg text-white overflow-hidden sticky top-8">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="font-bold text-lg text-white mb-1">Tambah Station Baru</h2>
                    <p class="text-gray-400 text-sm">Buat akun untuk laptop printer baru.</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('outlet.storeStation') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Nama Identitas</label>
                            <input type="text" name="name" placeholder="Contoh: PC Depan, PC Kasir 2" 
                                   class="w-full bg-gray-700 border-none rounded-lg p-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Email Login</label>
                            <input type="email" name="email" placeholder="email@print.com" 
                                   class="w-full bg-gray-700 border-none rounded-lg p-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Password</label>
                            <input type="password" name="password" placeholder="Min. 4 karakter" 
                                   class="w-full bg-gray-700 border-none rounded-lg p-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <div class="pt-4">
                            <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                                + Buat Akun Station
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection