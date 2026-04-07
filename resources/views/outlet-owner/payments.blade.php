<!-- filepath: c:\laragon\www\print-app\resources\views\outlet-owner\dashboard.blade.php -->
@extends('layouts.app-dashboard')
@section('title', 'Payments')
@section('child')

    {{-- KARTU UTAMA (DAFTAR FILE) --}}
    <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 flex-1 flex flex-col p-8">

        {{-- Judul & Search --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-2xl font-bold text-black">Daftar File</h2>

            {{-- Search Input --}}
            <form action="{{ route('outlet.payments') }}" method="GET" class="relative w-full md:w-64">

                {{-- Ikon Search --}}
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>

                {{-- Input Field --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID / File..."
                    class="w-full bg-[#FAFAFA] text-gray-700 text-sm rounded-lg border-none focus:outline-indigo-400 focus:bg-white py-2.5 pl-10 pr-4 transition-all"
                    onkeydown="if (event.key === 'Enter') this.form.submit()">

            </form>
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
                        {{-- BONGKAR DATA JSON PRINT CONFIG --}}
                        @php
                            // Pastikan data di-decode dengan benar menjadi array
                            $config = is_string($tx->print_config)
                                ? json_decode($tx->print_config, true)
                                : $tx->print_config ?? [];

                            $pages = $config['page_count'] ?? '-';
                            $copies = $config['copies'] ?? '-';
                            $colorMode = $config['color_mode'] ?? 'bw';
                            $paperSize = $config['paper_size'] ?? '-';
                        @endphp

                        <tr class="group hover:bg-gray-50 transition-colors">
                            {{-- ID --}}
                            <td class="py-5 pr-4 font-semibold text-gray-900">
                                {{ $tx->order_id }}
                            </td>

                            {{-- NOMINAL --}}
                            <td class="py-5 px-4 font-medium">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>

                            {{-- STATUS (Badge Style Figma) --}}
                            <td class="py-5 px-4">
                                @php
                                    $statusStyle = match ($tx->status) {
                                        'pending' => 'bg-yellow-50 text-yellow-600',
                                        'paid', 'processing' => 'bg-green-50 text-green-600',
                                        'rejected' => 'bg-red-50 text-red-600',
                                        'completed' => 'bg-blue-50 text-blue-600',
                                        default => 'bg-gray-100 text-gray-600',
                                    };

                                    $label = match ($tx->status) {
                                        'pending' => 'PENDING',
                                        'paid', 'processing' => 'DITERIMA',
                                        'rejected' => 'DITOLAK',
                                        default => strtoupper($tx->status),
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-md text-[10px] font-bold {{ $statusStyle }}">
                                    {{ $label }}
                                </span>
                            </td>

                            {{-- HALAMAN (Diambil dari $config) --}}
                            <td class="py-5 px-4">
                                {{ $pages }} Halaman
                            </td>

                            {{-- JUMLAH / COPIES (Diambil dari $config) --}}
                            <td class="py-5 px-4">
                                {{ $copies }} lembar
                            </td>

                            {{-- WARNA (Check untuk Color, Cross untuk Hitam Putih) --}}
                            <td class="py-5 px-4 text-center">
                                @if ($colorMode == 'color')
                                    {{-- Ikon Checkmark (Berwarna) --}}
                                    <svg class="w-5 h-5 text-gray-500 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    {{-- Ikon Silang (Hitam Putih) --}}
                                    <svg class="w-5 h-5 text-gray-300 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @endif
                            </td>

                            {{-- UKURAN KERTAS (Diambil dari $config) --}}
                            <td class="py-5 px-4 text-center font-medium">
                                {{ strtoupper($paperSize) }}
                            </td>

                            {{-- AKSI (Tombol Check / Cross Merah Hijau) --}}
                            <td class="py-5 px-4 text-right">
                                @if ($tx->status === 'pending')
                                    <div class="flex justify-end gap-2">
                                        {{-- Tombol Terima (Hijau) --}}
                                        <form action="{{ route('outlet.verify', $tx->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-6 h-6 rounded bg-[#4ADE80] text-white flex items-center justify-center hover:bg-green-500 transition shadow-sm cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak (Merah Pink) --}}
                                        <form action="{{ route('outlet.reject', $tx->id) }}" method="POST"
                                            class="reject-form">
                                            @csrf
                                            <button type="button" onclick="openRejectModal(this)"
                                                class="w-6 h-6 rounded bg-[#FB7185] text-white flex items-center justify-center hover:bg-red-500 transition shadow-sm cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
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
                <button
                    class="w-6 h-6 bg-white border rounded flex items-center justify-center font-bold text-black">1</button>
                <button class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center hover:bg-gray-200">›</button>
            </div>
        </div>

    </div>
    </div>
    </div>

    {{-- Modal Konfirmasi Tolak --}}
    <div id="rejectModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-96 text-center shadow-xl">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tolak Pembayaran?</h2>
            <p class="text-gray-500 mb-6">Transaksi ini akan ditolak dan pelanggan perlu membayar ulang.</p>

            <div class="flex gap-3">
                <button onclick="closeRejectModal()"
                    class="flex-1 py-3 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold transition-colors cursor-pointer">
                    Batal
                </button>
                <button onclick="confirmReject()"
                    class="flex-1 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700 font-semibold transition-colors cursor-pointer">
                    Ya, Tolak
                </button>
            </div>
        </div>
    </div>

    {{-- Script Realtime --}}
    <script>
        let selectedRejectForm = null;

        function openRejectModal(btn) {
            selectedRejectForm = btn.closest('form');
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            selectedRejectForm = null;
        }

        function confirmReject() {
            if (selectedRejectForm) {
                selectedRejectForm.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

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
