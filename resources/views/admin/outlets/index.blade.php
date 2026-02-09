@extends('layouts.app')
@section('child')
<div class="min-h-screen bg-gray-100 p-8">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Super Admin Dashboard</h1>
            <p class="text-gray-500">Kelola Data Mitra Outlet & Akses</p>
        </div>

        <div class="flex items-center gap-6">
            <a href="{{ route('admin.transactions') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 font-bold text-gray-700">
                Transactions
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-red-600 font-bold hover:underline">Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- FORM TAMBAH OUTLET --}}
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-lg mb-4 text-gray-700 border-b pb-2">Registrasi Mitra Baru</h3>
                <form action="{{ route('admin.outlets.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Nama Outlet / Toko</label>
                        <input type="text" name="outlet_name" class="w-full border rounded p-2" required placeholder="Contoh: Berkah Print">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Alamat</label>
                        <input type="text" name="address" class="w-full border rounded p-2" placeholder="Jl. Mawar No. 10">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Jatah Station (Laptop)</label>
                        <input type="number" name="max_stations" class="w-full border rounded p-2" required value="1" min="1">
                        <small class="text-gray-400">Berapa laptop yang boleh login.</small>
                    </div>

                    <div class="pt-4 border-t mt-4">
                        <p class="text-xs font-bold text-blue-600 mb-2 uppercase">Data Akun Owner</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Nama Pemilik</label>
                                <input type="text" name="owner_name" class="w-full border rounded p-2 text-sm" required placeholder="Nama Owner">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Email Login</label>
                                <input type="email" name="owner_email" class="w-full border rounded p-2 text-sm" required placeholder="email@owner.com">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600">Password Login</label>
                                <div class="relative">
                                    <input type="password" name="owner_password" class="w-full border rounded p-2 text-sm" required placeholder="Buat password untuk owner">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded mt-4 hover:bg-blue-700 transition">
                        SIMPAN DATA
                    </button>
                </form>
            </div>
        </div>

        {{-- TABEL DAFTAR OUTLET --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-gray-600">Nama Outlet</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Kuota Station</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($outlets as $outlet)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $outlet->name }}</div>
                                <div class="text-xs text-gray-500">{{ $outlet->address }}</div>
                            </td>
                            <td class="p-4">
                                @if($outlet->owner)
                                    <div class="text-sm font-medium">{{ $outlet->owner->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $outlet->owner->email }}</div>
                                @else
                                    <span class="text-red-500 text-xs">Belum ada akun</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">
                                    Max: {{ $outlet->max_stations }} PC
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="text-green-600 text-xs font-bold uppercase">Aktif</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($outlets->isEmpty())
                    <div class="p-8 text-center text-gray-500">Belum ada data mitra outlet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
