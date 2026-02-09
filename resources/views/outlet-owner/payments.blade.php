<!-- filepath: c:\laragon\www\print-app\resources\views\outlet-owner\dashboard.blade.php -->
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

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <p class="font-bold">{{ session('success') }}</p>
                <span class="text-xl cursor-pointer" onclick="this.parentElement.remove()">&times;</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <p class="font-bold">{{ session('error') }}</p>
                <span class="text-xl cursor-pointer" onclick="this.parentElement.remove()">&times;</span>
            </div>
        @endif


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">

                {{-- Header Bagian Kiri --}}
                <div class="mb-4 flex justify-between items-center">
                    <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                        @if ($transactions->isNotEmpty())
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                        Pembayaran Masuk
                    </h2>
                    <span class="bg-white border text-gray-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        {{ $transactions->count() }} Menunggu
                    </span>
                </div>

                {{-- Table Container style Admin --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-sm font-semibold text-gray-600">Order ID</th>
                                <th class="p-4 text-sm font-semibold text-gray-600">File & Station</th>
                                <th class="p-4 text-sm font-semibold text-gray-600">Amount</th>
                                <th class="p-4 text-sm font-semibold text-gray-600">Status</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 align-top">
                                        <div class="font-bold text-gray-800">#{{ $tx->order_id }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $tx->created_at->diffForHumans() }}</div>
                                    </td>

                                    <td class="p-4 align-top">
                                        <div class="font-semibold text-gray-800 mb-1">
                                            {{ $tx->filename_snapshot ?? ($tx->file->original_name ?? '(File Terhapus)') }}
                                        </div>

                                        <div class="flex items-center gap-2 text-xs">
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200">
                                                Station: {{ $tx->station->name ?? 'Unknown' }}
                                            </span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-gray-500">{{ $tx->total_pages }} Hlm / {{ $tx->copies }}
                                                Rangkap</span>
                                        </div>
                                    </td>

                                    <td class="p-4 align-top">
                                        <div class="font-bold text-gray-800">
                                            Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-400">Tunai</div>
                                    </td>

                                    <td class="p-4 align-top">
                                        @php
                                            $badge = match ($tx->status) {
                                                'pending', 'waiting_verification' => 'bg-yellow-100 text-yellow-800',
                                                'paid', 'processing' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                            $label = $tx->status == 'waiting_verification' ? 'MENUNGGU' : $tx->status;
                                        @endphp

                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                            {{ strtoupper($label) }}
                                        </span>
                                    </td>

                                    <td class="p-4 align-top text-right">
                                        @if ($tx->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                {{-- Tombol Tolak --}}
                                                <form action="{{ route('outlet.reject', $tx->id) }}" method="POST"
                                                    onsubmit="return confirm('Tolak transaksi ini?')">
                                                    @csrf
                                                    <button
                                                        class="bg-red-600 hover:bg-red-500 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow-sm">
                                                        Reject
                                                    </button>
                                                </form>

                                                {{-- Tombol Terima --}}
                                                <form action="{{ route('outlet.verify', $tx->id) }}" method="POST">
                                                    @csrf
                                                    <button
                                                        class="bg-green-600 hover:bg-green-500 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow-sm">
                                                        Approve
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 font-bold">No action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center bg-white">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="font-medium">Tidak ada antrian pembayaran saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                // Gunakan outlet_id dari owner yang sedang login
                const OUTLET_ID = @json(Auth::user()->outlet_id);

                window.Echo.channel(`outlet-channel.${OUTLET_ID}`)
                    .listen('.transaction.created', (e) => {
                        console.log('Ada pembayaran baru masuk!');
                        location.reload();
                    });
            }
        });
    </script>
@endsection
