<!-- filepath: c:\laragon\www\print-app\resources\views\outlet-owner\dashboard.blade.php -->
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

        <div class="h-full flex flex-col flex-1">
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-[#FAFAFA] p-6 rounded-xl">
                <div>
                    <h1 class="text-4xl text-gray-900 font-koulen">PAYMENTS</h1>
                    {{-- <h1 class="text-gray-500">Selamat datang, {{ Auth::user()->name }}</h1> --}}
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-gray-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24"><path fill="#6155F5" fill-rule="evenodd" d="M8 7a4 4 0 1 1 8 0a4 4 0 0 1-8 0m0 6a5 5 0 0 0-5 5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3a5 5 0 0 0-5-5z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="flex flex-col text-right">
                        <p class="font-bold text-lg">
                            {{ Auth::user()->outlet->name ?? 'Outlet' }}
                        </p>
                        <p class="text-md text-[#B1B0AB]">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                    {{-- <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                        Paket: {{ $outlet->max_stations }} Station
                    </span> --}}
                </div>
            </div>

            {{-- KARTU UTAMA (DAFTAR PERANGKAT) --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 flex-1 flex flex-col p-8">
                
                {{-- Judul & Search --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <h2 class="text-2xl font-bold text-black">Daftar File</h2>
                    
                    {{-- Search Input --}}
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" placeholder="Cari file" class="w-full bg-[#FAFAFA] text-gray-700 text-sm rounded-lg border-none focus:ring-0 focus:bg-gray-100 py-2.5 pl-10 pr-4">
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[#8E8D8D] text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                                <th class="py-4 pr-4">ID</th>
                                <th class="py-4 px-4">NOMINAL</th>
                                <th class="py-4 px-4">STATUS</th>
                                <th class="py-4 px-4">HALAMAN</th>
                                <th class="py-4 px-4">JUMLAH</th>
                                <th class="py-4 px-4 text-center">WARNA</th>
                                <th class="py-4 px-4 text-center">UKURAN</th>
                                <th class="py-4 px-4 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($transactions as $tx)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    {{-- ID --}}
                                    <td class="py-5 pr-4 font-semibold text-gray-900">
                                        {{ $tx->order_id }}
                                    </td>

                                    {{-- NOMINAL --}}
                                    <td class="py-5 px-4 font-medium">
                                        Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="py-5 px-4">
                                        @php
                                            $statusStyle = match($tx->status) {
                                                'pending' => 'bg-yellow-50 text-yellow-600',
                                                'paid', 'processing' => 'bg-green-50 text-green-600', 
                                                'rejected' => 'bg-red-50 text-red-600',
                                                'completed' => 'bg-blue-50 text-blue-600',
                                                default => 'bg-gray-100 text-gray-600'
                                            };
                                            
                                            $label = match($tx->status) {
                                                'pending' => 'PENDING',
                                                'paid', 'processing' => 'DITERIMA', 
                                                'rejected' => 'DITOLAK',
                                                default => strtoupper($tx->status)
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-md text-[10px] font-bold {{ $statusStyle }}">
                                            {{ $label }}
                                        </span>
                                    </td>

                                    {{-- HALAMAN --}}
                                    <td class="py-5 px-4">
                                        {{ $tx->total_pages }} Halaman
                                    </td>

                                    {{-- JUMLAH --}}
                                    <td class="py-5 px-4">
                                        {{ $tx->copies }} lembar
                                    </td>

                                    {{-- WARNA --}}
                                    <td class="py-5 px-4 text-center">
                                        @if($tx->color_mode == 'color')
                                            <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @endif
                                    </td>

                                    {{-- UKURAN --}}
                                    <td class="py-5 px-4 text-center font-medium">
                                        {{ $tx->paper_size ?? 'A4' }}
                                    </td>

                                    {{-- AKSI (Tombol Check) --}}
                                    <td class="py-5 px-4 text-right">
                                        @if($tx->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                {{-- Tombol Terima --}}
                                                <form action="{{ route('outlet.verify', $tx->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-6 h-6 rounded bg-[#4ADE80] text-white flex items-center justify-center hover:bg-green-500 transition shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>

                                                {{-- Tombol Tolak --}}
                                                <form action="{{ route('outlet.reject', $tx->id) }}" method="POST" onsubmit="return confirm('Tolak?')">
                                                    @csrf
                                                    <button type="submit" class="w-6 h-6 rounded bg-[#FB7185] text-white flex items-center justify-center hover:bg-red-500 transition shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-300 text-lg mx-auto block w-fit">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-gray-400">
                                        Tidak ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-auto pt-6 flex justify-between items-center text-xs text-gray-400">
                    <div>Showing 1 to {{ $transactions->count() }} of {{ $transactions->count() }} entries</div>
                    <div class="flex gap-1">
                        <button class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center hover:bg-gray-200">‹</button>
                        <button class="w-6 h-6 bg-white border rounded flex items-center justify-center font-bold text-black">1</button>
                        <button class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center hover:bg-gray-200">›</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Realtime --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                const OUTLET_ID = @json(Auth::user()->outlet_id);
                window.Echo.channel(`outlet-channel.${OUTLET_ID}`)
                    .listen('.transaction.created', (e) => {
                        location.reload();
                    });
            }
        });
    </script>
@endsection
