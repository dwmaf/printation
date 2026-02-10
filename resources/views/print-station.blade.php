@extends('layouts.app')
@section('child')

<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<div class="h-screen p-4 flex flex-col bg-[#FAFAFA] items-center justify-center">
    
    @if($files->isEmpty())
    {{-- HEADER (UI versi bawah) --}}
    <div class="h-30 flex mb-8">
        <img src="{{ asset('images/logo.png') }}" class="w-18">
        <h1 class="text-5xl font-koulen">Printation</h1>
    </div>

    <h2 class="uppercase font-medium text-gray-400 mb-4 font-roboto">{{ Auth::user()->name }}</h2>
        {{-- EMPTY STATE (UI versi bawah) --}}
        <div class="w-fit h-96 flex flex-col items-center justify-center bg-white p-6 rounded-xl shadow-lg">
            <p class="text-gray-400 mb-8">Scan QR di bawah ini untuk mulai upload file.</p>

            <div class="relative w-full bg-white mb-8 overflow-hidden [&>svg]:w-full [&>svg]:h-full">
                {!! $qrCode !!}
            </div>

            <div class="flex items-center space-x-3 bg-[#ECECEC] px-6 py-3 rounded-full">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                <span class="text-sm font-medium">Menunggu upload file...</span>
            </div>
        </div>
        @else
        <div class="bg-white w-full h-full rounded-xl shadow-lg px-8">
            {{-- HEADER (UI versi bawah) --}}
            <div class="h-30 flex items-center">
                <img src="{{ asset('images/logo.png') }}" class="w-18">
                <h1 class="text-5xl font-koulen">Printation</h1>
            </div>

            {{-- LIST FILES (UI versi bawah) --}}
            <div class="w-full max-h-[80vh] overflow-y-auto custom-scrollbar">
                <h2 class="uppercase font-medium text-gray-400 font-roboto">{{ Auth::user()->name }}</h2>
            <div class="mb-8 flex items-center gap-3 h-10">
                    {{-- <h2 class="text-2xl font-bold mb-4">{{ Auth::user()->name }}</h2> --}}
                    <p id="fileCounter" class="text-lg font-semibold text-gray-700">0 file dipilih</p>
                    <button id="deleteAllBtn" onclick="deleteSelectedFiles()"
                            class="hidden bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-all cursor-pointer">
                        Hapus semua
                    </button>
                </div>

                <table class="w-full border-collapse">
                    <thead class="bg-gray-100 sticky top-0 z-50">
                        <tr class="text-left text-sm font-semibold text-gray-700">
                            <th class="p-3 text-center">
                                <input type="checkbox" id="checkAll" class="w-4 h-4 accent-blue-600 rounded cursor-pointer">
                            </th>
                            <th class="p-3 text-lg text-center">Tipe</th>
                            <th class="p-3 text-lg">Nama File</th>
                            <th class="p-3 text-lg">Diunggah</th>
                            <th class="p-3 text-lg text-center">Status</th>
                            <th class="p-3 text-lg text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach($files as $file)
                            @php
                                // URL RELATIVE (aman untuk ngrok walau APP_URL salah)
                                $fileUrl = '/storage/' . ltrim($file->filename, '/');

                                // transaksi terbaru (relasi transactions harus order latest)
                                $tx = $file->transactions->first();
                                $status = $tx->status ?? null;

                                // parse print_config (JSON) jika ada
                                $paidConfig = null;
                                if ($tx && !empty($tx->print_config)) {
                                    $paidConfig = json_decode($tx->print_config, true);
                                }

                                // hitung halaman (simple) - default 1
                                $isPdf = strtoupper($file->type ?? '') === 'PDF' || str_ends_with(strtolower($file->filename), '.pdf');
                                $filePath = storage_path('app/public/' . $file->filename);
                                $pageCount = 1;

                                if ($isPdf && file_exists($filePath)) {
                                    $content = @file_get_contents($filePath);
                                    if ($content) {
                                        $count = preg_match_all("/\/Type\s*\/Page[^s]/", $content, $matches);
                                        if ($count > 0) $pageCount = $count;
                                    }
                                }

                                // status badge
                                $badgeText = 'BELUM BAYAR';
                                $badgeClass = 'bg-gray-100 text-gray-700';

                                if ($tx) {
                                    if ($status === 'pending') {
                                        $badgeText = 'MENUNGGU KONFIRMASI';
                                        $badgeClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($status === 'paid') {
                                        $badgeText = 'PAID (SIAP CETAK)';
                                        $badgeClass = 'bg-green-100 text-green-800';
                                    } elseif ($status === 'rejected') {
                                        $badgeText = 'DITOLAK (BAYAR ULANG)';
                                        $badgeClass = 'bg-red-100 text-red-700';
                                    } else {
                                        $badgeText = strtoupper((string)$status);
                                        $badgeClass = 'bg-gray-100 text-gray-700';
                                    }
                                }

                                // tombol utama (label)
                                $mainLabel = 'BAYAR';
                                $mainBtnClass = 'bg-[#6155F5] hover:bg-[#6155F5] text-white cursor-pointer';
                                $iconFill = '#fff'; // default putih
                                $mainMode = 'pay';

                                if ($tx) {
                                    if ($status === 'pending') {
                                        $mainLabel = 'DETAIL';
                                        $mainBtnClass = 'bg-[#6155F5] cursor-pointer';
                                        $mainMode = 'pending';
                                    } elseif ($status === 'paid') {
                                        $mainLabel = 'PRINT';
                                        $mainBtnClass = 'bg-[#6155F5] cursor-pointer';
                                        $mainMode = 'paid';
                                    } elseif ($status === 'rejected') {
                                        $mainLabel = 'BAYAR ULANG';
                                        $mainBtnClass = 'bg-[#6155F5] cursor-pointer';
                                        $mainMode = 'pay';
                                    } 
                                    // elseif ($status === 'completed') {
                                    //     $badgeText = 'SELESAI DICETAK';
                                    //     $badgeClass = 'bg-blue-100 text-blue-800';
                                        
                                    //     // Opsional 1: Kalau mau bisa bayar lagi buat print copy baru
                                    //     $mainLabel = 'BAYAR LAGI';
                                    //     $mainBtnClass = 'bg-gray-200 hover:bg-gray-300 text-gray-800';
                                    //     $mainMode = 'pay'; // Reset ke mode bayar
                                        
                                    //     // Opsional 2: Kalau mau menonaktifkan tombol
                                    //     // $mainLabel = 'SELESAI';
                                    //     // $mainBtnClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                    //     // $mainMode = 'completed'; 
                                    // }
                                }

                                // payload untuk LOGIKA (jangan diubah)
                                $openPayload = [
                                    'file_id' => $file->id,
                                    'url' => $fileUrl,
                                    'pages' => $pageCount,
                                    'type' => $file->type ?? 'FILE',
                                    'original_name' => $file->original_name ?? $file->filename,
                                    'tx_id' => $tx->id ?? null,
                                    'tx_status' => $status,
                                    'order_id' => $tx->order_id ?? null,
                                    'amount' => $tx->amount ?? null,
                                    'paid_config' => $paidConfig,
                                    'mode' => $mainMode,
                                ];
                            @endphp

                            <tr class="hover:bg-gray-50 transition text-center">
                                <td class="p-3">
                                    <input type="checkbox"
                                           class="row-check w-4 h-4 accent-blue-600 rounded cursor-pointer"
                                           data-file-id="{{ $file->id }}">
                                </td>

                                <td class="p-3 font-bold {{ $isPdf ? 'text-red-600' : 'text-blue-600' }}">
                                    {{ strtoupper($file->type ?? 'FILE') }}
                                </td>

                                <td class="p-3 truncate max-w-[300px] text-left">
                                    {{ $file->original_name ?? $file->filename }}
                                    @if($tx && !empty($tx->order_id))
                                        <div class="text-xs text-yellow-950 font-semibold mt-1 bg-yellow-100 w-fit p-1 rounded-sm">
                                            ORDER: <span class="text-yellow-800">{{ $tx->order_id }}</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="p-3 text-sm text-gray-500 text-left">
                                    {{ $file->created_at->diffForHumans() }}
                                </td>

                                <td class="p-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $badgeText }}
                                    </span>
                                </td>

                                <td class="p-3">
                                    <div class="flex justify-center gap-2">
                                        {{-- tombol utama: BAYAR/DETAIL/PRINT sesuai status --}}
                                        <div class="relative group">
                                            <button type="button"
                                                    onclick='openPrintModal(@json($openPayload))'
                                                    data-tooltip="{{ $mainLabel }}"
                                                    class="p-2 rounded-lg font-bold shadow-sm {{ $mainBtnClass }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 48 48"><path fill="{{ $iconFill }}" d="M16.25 8h15.5a3.25 3.25 0 0 1 3.245 3.066l.005.184v2.749l1.75.001c2.9 0 5.25 2.35 5.25 5.25v13.5A3.25 3.25 0 0 1 38.75 36L35 35.999v1.751A3.25 3.25 0 0 1 31.75 41h-15.5A3.25 3.25 0 0 1 13 37.75v-1.751L9.25 36A3.25 3.25 0 0 1 6 32.75v-13.5C6 16.35 8.35 14 11.25 14l1.75-.001V11.25a3.25 3.25 0 0 1 3.066-3.245zm15.5 20.5h-15.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h15.5a.75.75 0 0 0 .75-.75v-8.5a.75.75 0 0 0-.75-.75m5-12h-25.5a2.75 2.75 0 0 0-2.75 2.75v13.5c0 .414.336.75.75.75l3.75-.001V29.25A3.25 3.25 0 0 1 16.25 26h15.5A3.25 3.25 0 0 1 35 29.25v4.249l3.75.001a.75.75 0 0 0 .75-.75v-13.5a2.75 2.75 0 0 0-2.75-2.75m-5-6h-15.5a.75.75 0 0 0-.743.648l-.007.102v2.749h17V11.25a.75.75 0 0 0-.648-.743z"/></svg>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                                                hidden group-hover:block
                                                bg-white text-xs font-medium px-2 py-1 rounded shadow
                                                whitespace-nowrap z-50">
                                            Print</span>
                                        </div>

                                        {{-- delete --}}
                                        <form action="{{ route('station.destroy', $file->id) }}" method="POST" class="delete-file">
                                            @csrf
                                            @method('DELETE')
                                            <div class="relative group">
                                                <button type="button"
                                                        class="bg-red-600 text-gray-800 hover:text-white p-2 rounded-lg font-bold transition-colors cursor-pointer"
                                                        onclick="openDeleteModal(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24"><path fill="#fff" d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/></svg>
                                                </button>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                                                hidden group-hover:block
                                                bg-white text-xs font-medium px-2 py-1 rounded shadow
                                                whitespace-nowrap z-50">
                                            Hapus</span>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- DELETE MODAL (UI versi bawah) --}}
        <div id="deleteModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 w-[350px] text-center shadow-xl">
                <h2 id="deleteModalTitle" class="text-xl font-bold mb-3">Hapus File?</h2>
                <p id="deleteModalMessage" class="text-gray-500 mb-6">File akan dihapus permanen.</p>

                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()"
                            class="w-full py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button onclick="confirmDelete()"
                            class="w-full py-2 rounded-lg bg-red-600 text-white hover:bg-red-500 font-semibold cursor-pointer">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- QR MODAL (untuk memperbesar QR) --}}
        <div id="qrModal" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[100] backdrop-blur-sm" onclick="closeQrModal()">
            <div class="relative bg-white rounded-2xl p-6 max-w-md shadow-2xl" onclick="event.stopPropagation()">
                <!-- Tombol X Merah Menonjol -->
                <button onclick="closeQrModal()" class="absolute top-20 left-115 bg-red-600 hover:bg-red-700 text-white font-bold text-2xl w-12 h-12 rounded-full shadow-2xl transition-all hover:scale-125 z-50 flex items-center justify-center">
                    ✕
                </button>
                <h3 class="text-xl font-bold text-center mb-4">QRIS Pembayaran</h3>
                <div class="bg-white p-4 border-2 border-gray-200 rounded-2xl">
                    <img src="{{ $outletQr }}" class="w-full h-auto object-contain" alt="QRIS">
                </div>
                <p class="text-sm text-gray-500 text-center mt-4">
                    Scan QRIS menggunakan aplikasi pembayaran Anda
                </p>
            </div>
        </div>
    </div>
</div>

{{-- PRINT MODAL (UI “bawah” tapi panelnya tetap lengkap utk logika “atas”) --}}
<div id="printModal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity opacity-0"
     style="transition: opacity 0.25s ease-out;">
    <div id="modalContent"
         class="bg-white rounded-2xl overflow-hidden w-full max-w-6xl h-[85vh] flex shadow-2xl scale-95 transition-transform"
         style="transition: transform 0.25s ease-out;">

        {{-- LEFT: PREVIEW --}}
        <div class="w-2/3 bg-gray-200 relative flex items-center justify-center border-r border-gray-300">
            <div id="loadingSpinner" class="absolute flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mb-3"></div>
                <p class="text-gray-500 font-bold">Memuat Preview...</p>
            </div>

            <iframe id="previewFrame" class="w-full h-full relative z-10 bg-white" src=""></iframe>
            <div class="absolute inset-0 z-20 pointer-events-none"></div>
        </div>

        {{-- RIGHT: PANEL --}}
        <div class="w-1/3 bg-gray-50 flex flex-col h-full overflow-hidden relative">

            {{-- HEADER --}}
            <div class="flex justify-between items-start p-6 pb-3 shrink-0 bg-gray-50 z-10 border-b border-gray-200">
                <div>
                    <h2 id="panelTitle" class="text-xl font-black text-gray-900 uppercase tracking-wide">Konfigurasi</h2>
                    <p id="panelSubTitle" class="text-xs text-gray-500 font-bold mt-1"></p>
                </div>
                <button onclick="closePrintModal()"
                        class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- BODY --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">

                {{-- PANEL: CONFIG (PAY MODE) --}}
                <div id="panelConfig" class="space-y-5">

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm font-bold text-gray-900" id="fileNameConfig"></div>
                        <div class="text-xs text-gray-400 font-bold mt-1" id="fileMetaConfig"></div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ukuran Kertas</label>
                        <select id="printPaperSize"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 font-bold">
                            <option value="A4" selected>A4</option>
                            <option value="Legal">Legal / F4</option>
                        </select>
                    </div>

                    <div id="containerPageRange" class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rentang Halaman</label>

                        <div class="flex items-center mb-3 space-x-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="all" checked
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="ml-2 text-sm font-medium text-gray-900">Semua</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="custom"
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="ml-2 text-sm font-medium text-gray-900">Custom</span>
                            </label>
                        </div>

                        <div id="customPageInputDiv" class="hidden">
                            <input id="printPageRange" type="text" placeholder="Contoh: 1-5, 8, 11-13"
                                   class="w-full text-sm font-bold border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <p class="text-[10px] text-gray-400 mt-1 font-semibold">
                                Gunakan tanda hubung (-) untuk rentang dan koma (,) untuk halaman acak.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Copy</label>
                        <div class="flex items-center">
                            <button type="button" onclick="adjustCopies(-1)"
                                    class="w-10 h-10 bg-gray-100 rounded-l-lg hover:bg-gray-200 font-bold cursor-pointer">-</button>
                            <input id="printCopies" type="number" value="1" min="1" readonly
                                   class="w-full text-center text-xl font-bold text-gray-800 border-y border-x-0 border-gray-200 h-10 focus:ring-0">
                            <button type="button" onclick="adjustCopies(1)"
                                    class="w-10 h-10 bg-gray-100 rounded-r-lg hover:bg-gray-200 font-bold cursor-pointer">+</button>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mode Warna</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="colorMode" value="color" checked class="peer sr-only">
                                <div class="p-2 rounded-lg border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center transition-all hover:bg-gray-50">
                                    <span class="font-bold text-sm block text-gray-700">Berwarna</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="colorMode" value="bw" class="peer sr-only">
                                <div class="p-2 rounded-lg border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center transition-all hover:bg-gray-50">
                                    <span class="font-bold text-sm block text-gray-700">Hitam Putih</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mt-6">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-1 font-semibold">
                            <span>Harga per lembar</span>
                            <span id="displayPricePerSheet" class="font-bold">Rp 500</span>
                        </div>

                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2 font-semibold">
                            <span>Kalkulasi</span>
                            <span id="displayCalculation" class="font-bold">1 Hal x 1 Copy</span>
                        </div>

                        <div class="border-t border-blue-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-blue-900 text-lg">TOTAL BAYAR</span>
                            <span id="displayTotalPrice" class="font-black text-2xl text-blue-600">Rp 500</span>
                        </div>
                    </div>

                    <div class="pt-2 space-y-3">
                        <button type="button" onclick="goToPayment()"
                                class="w-full py-4 bg-yellow-500 hover:bg-yellow-400 text-white rounded-xl font-black text-lg shadow-lg transition-all">
                            LANJUT PEMBAYARAN
                        </button>
                        <button type="button" onclick="closePrintModal()"
                                class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>

                {{-- PANEL: PAYMENT --}}
                <div id="panelPayment" class="hidden space-y-5">
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm font-bold text-gray-900">Silakan transfer dengan nominal tepat:</div>
                        <div id="payAmount" class="mt-3 bg-blue-600 text-white font-black text-4xl py-4 px-4 rounded-2xl text-center shadow-lg shadow-blue-500/20">
                            Rp 0
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                        <div class="text-xs font-bold text-gray-500 uppercase mb-3">QRIS Pembayaran</div>
                        <div class="inline-block bg-white p-2 border-2 border-gray-200 rounded-2xl shadow-sm cursor-pointer hover:shadow-md transition-shadow" onclick="openQrModal()">
                            <img id="payQrImage" src="{{ $outletQr }}" class="w-56 h-56 object-contain" alt="QRIS">
                        </div>
                        <p class="text-xs text-gray-400 mt-3 font-semibold">
                            Scan QRIS di atas menggunakan GoPay / OVO / Dana / Mobile Banking.
                        </p>
                        <p class="text-xs text-blue-600 mt-1 font-semibold">
                            Klik gambar untuk memperbesar
                        </p>
                    </div>

                    <div class="space-y-2">
                        <button id="btnAlreadyTransfer" type="button" onclick="submitManualTransaction(event)"
                                class="w-full py-4 bg-green-600 hover:bg-green-500 text-white rounded-xl font-black text-lg shadow-lg transition-all">
                            SAYA SUDAH TRANSFER
                        </button>

                        <button type="button" onclick="backToConfig()"
                                class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                            Kembali
                        </button>
                    </div>
                </div>

                {{-- PANEL: WAITING --}}
                <div id="panelWaiting" class="hidden space-y-5">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>

                        <h3 class="text-xl font-black text-gray-900">MENUNGGU KONFIRMASI ADMIN</h3>
                        <p class="text-gray-500 font-semibold text-sm mt-2">
                            Tunjukkan kode ini ke admin/kasir.
                        </p>

                        <div class="mt-5 bg-gray-900 text-white font-black text-4xl py-4 rounded-2xl tracking-widest">
                            <span id="waitingOrderId">-</span>
                        </div>

                        <div class="mt-4 text-sm font-bold text-gray-700">
                            Status: <span class="text-yellow-700">PENDING</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <button type="button" onclick="location.reload()"
                                class="w-full py-4 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-black text-lg">
                            Refresh Status
                        </button>

                        <button type="button" onclick="closePrintModal(true)"
                                class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>

                {{-- PANEL: PAID / PRINT --}}
                <div id="panelPaid" class="hidden space-y-5">
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm font-bold text-gray-900" id="fileNamePaid"></div>
                        <div class="text-xs text-gray-400 font-bold mt-1" id="fileMetaPaid"></div>
                    </div>

                    <div class="bg-green-50 border border-green-200 p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="font-black text-green-900">STATUS: PAID</div>
                            <div class="text-xs font-bold text-green-800">Siap cetak</div>
                        </div>

                        <div class="mt-3 text-sm text-gray-700 font-semibold">
                            Order ID:
                            <span class="font-black text-gray-900" id="paidOrderId">-</span>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="text-xs font-bold text-gray-500 uppercase mb-3">Konfigurasi (terkunci)</div>
                        <div class="grid grid-cols-2 gap-3 text-sm font-black text-gray-900">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold">Paper</div>
                                <div id="lockedPaper">-</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold">Warna</div>
                                <div id="lockedColor">-</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold">Copy</div>
                                <div id="lockedCopies">-</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold">Halaman</div>
                                <div id="lockedPages">-</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t pt-3">
                            <div class="text-sm font-black text-gray-700">TOTAL</div>
                            <div class="text-2xl font-black text-blue-600" id="lockedTotal">Rp 0</div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <button id="btnPrintNow" type="button" onclick="sendPrintJob(event)"
                                class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-black text-lg shadow-lg transition-all">
                            CETAK SEKARANG
                        </button>

                        <button type="button" onclick="closePrintModal()"
                                class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>

                {{-- PANEL: PRINTING --}}
                <div id="panelPrinting" class="hidden space-y-5">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 text-blue-700 flex items-center justify-center mb-4">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-4 border-blue-600"></div>
                        </div>

                        <h3 class="text-xl font-black text-gray-900">MENCETAK...</h3>
                        <p class="text-gray-500 font-semibold text-sm mt-2">
                            Mengirim job ke server print. Jangan tutup halaman.
                        </p>

                        <div class="mt-5 bg-gray-900 text-white font-black text-4xl py-4 rounded-2xl tracking-widest">
                            <span id="printingOrderId">-</span>
                        </div>
                    </div>

                    <button type="button" onclick="closePrintModal()"
                            class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const STATION_ID = @json(Auth::id());

    // Inisialisasi Echo (pastikan app.js sudah memuat Echo & Socket.io/Reverb client)
    // Jika menggunakan Vite secara standar:
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel(`printing-channel.${STATION_ID}`)
                .listen('.file.uploaded', (e) => {
                    console.log('File baru terdeteksi, merefresh...');
                    // Cara termudah: reload halaman agar list file terbaru muncul
                    location.reload(); 
                    
                    // Atau jika ingin lebih smooth, Anda bisa memanggil fungsi 
                    // AJAX untuk mengambil ulang data <tbody> saja.
                })
                .listen('.transaction.updated', (e) => {
                    console.log('Status transaksi diperbarui oleh admin...');
                    location.reload();
                });
        }
    });
    // ========= ENDPOINTS (RELATIVE URL = aman untuk NGROK walau APP_URL salah) =========
    const ENDPOINT_TX_STORE   = @json(route('transaction.store', [], false));
    const ENDPOINT_PRINT      = @json(route('process.print', [], false));
    const CSRF_TOKEN          = @json(csrf_token());

    // ========= PRICE RULE =========
    const PRICE_BW = 500;
    const PRICE_COLOR = 1000;

    // ========= MODAL ELEMENTS =========
    const modal        = document.getElementById('printModal');
    const modalContent = document.getElementById('modalContent');
    const previewFrame = document.getElementById('previewFrame');
    const spinner      = document.getElementById('loadingSpinner');

    const panelTitle    = document.getElementById('panelTitle');
    const panelSubTitle = document.getElementById('panelSubTitle');

    const panelConfig   = document.getElementById('panelConfig');
    const panelPayment  = document.getElementById('panelPayment');
    const panelWaiting  = document.getElementById('panelWaiting');
    const panelPaid     = document.getElementById('panelPaid');
    const panelPrinting = document.getElementById('panelPrinting');

    // ========= STATE =========
    let current = {
        fileId: null,
        fileUrl: null,
        fileName: null,
        fileType: null,
        pages: 1,

        txId: null,
        txStatus: null,
        orderId: null,
        amount: null,
        paidConfig: null,

        mode: 'pay', // pay | pending | paid
    };

    let pendingConfig = {}; // payload to transaction.store
    let selectedDeleteForm = null;

    // ========= HELPERS =========
    function formatRp(num) {
        const n = Number(num || 0);
        return "Rp " + n.toLocaleString('id-ID');
    }

    function showOnly(panelEl) {
        [panelConfig, panelPayment, panelWaiting, panelPaid, panelPrinting].forEach(p => p.classList.add('hidden'));
        panelEl.classList.remove('hidden');
    }

    // ========= OPEN / CLOSE MODAL =========
    function openPrintModal(opts) {
        // Set state
        current.fileId     = opts.file_id;
        current.fileUrl    = opts.url;
        current.fileName   = opts.original_name || 'FILE';
        current.fileType   = opts.type || 'FILE';
        current.pages      = parseInt(opts.pages || 1);

        current.txId       = opts.tx_id || null;
        current.txStatus   = opts.tx_status || null;
        current.orderId    = opts.order_id || null;
        current.amount     = opts.amount || null;
        current.paidConfig = opts.paid_config || null;
        current.mode       = opts.mode || 'pay';

        // Reset preview
        previewFrame.src = '';
        spinner.style.display = 'flex';
        previewFrame.onload = () => { spinner.style.display = 'none'; };

        // Load preview (PDF toolbar hidden)
        previewFrame.src = current.fileUrl + "#toolbar=0&navpanes=0&scrollbar=0&view=FitH";

        // Decide mode from txStatus (lebih aman dari server)
        // Jika status selesai (completed), kita paksa mode 'pay' agar bisa bayar lagi
        if (!current.txStatus || current.txStatus === 'rejected' || current.txStatus === 'completed') {
            current.mode = 'pay';
        } else if (current.txStatus === 'pending') {
            current.mode = 'pending';
        } else if (current.txStatus === 'paid') {
            current.mode = 'paid';
        } else {
            current.mode = 'pending';
        }

        // Render right panel
        if (current.mode === 'pay') {
            panelTitle.innerText = 'Konfigurasi';
            panelSubTitle.innerText = 'Atur cetak & lanjut pembayaran';
            renderConfigPanel();
            showOnly(panelConfig);
        } else if (current.mode === 'pending') {
            panelTitle.innerText = 'Status Transaksi';
            panelSubTitle.innerText = 'Menunggu konfirmasi admin';
            document.getElementById('waitingOrderId').innerText = current.orderId || '-';
            showOnly(panelWaiting);
        } else if (current.mode === 'paid') {
            panelTitle.innerText = 'Siap Cetak';
            panelSubTitle.innerText = 'Konfigurasi terkunci sesuai pembayaran';
            renderPaidPanel();
            showOnly(panelPaid);
        }

        // Open modal animation
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closePrintModal(forceReload = false) {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            previewFrame.src = '';
            if (forceReload) location.reload();
        }, 250);
    }

    // ========= CONFIG PANEL LOGIC =========
    function renderConfigPanel() {
        document.getElementById('fileNameConfig').innerText = current.fileName;
        document.getElementById('fileMetaConfig').innerText = `${current.fileType} • ${current.pages} halaman`;

        // reset inputs
        document.getElementById('printPaperSize').value = 'A4';
        document.getElementById('printCopies').value = 1;
        document.querySelector('input[name="pageOption"][value="all"]').checked = true;
        document.querySelector('input[name="colorMode"][value="color"]').checked = true;
        document.getElementById('printPageRange').value = '';
        togglePageInput();
        calculateTotal();
    }

    function adjustCopies(amount) {
        const input = document.getElementById('printCopies');
        let val = parseInt(input.value || 1) + amount;
        if (val < 1) val = 1;
        input.value = val;
        calculateTotal();
    }

    function togglePageInput() {
        const isCustom = document.querySelector('input[name="pageOption"]:checked').value === 'custom';
        const div = document.getElementById('customPageInputDiv');
        const customInput = document.getElementById('printPageRange');

        if (isCustom) {
            div.classList.remove('hidden');
            customInput.focus();
        } else {
            div.classList.add('hidden');
            customInput.value = '';
        }
        calculateTotal();
    }

    function countCustomPages(rangeString) {
        if (!rangeString) return 0;
        const parts = rangeString.replace(/\s/g, '').split(',');
        let count = 0;

        parts.forEach(part => {
            if (part.includes('-')) {
                const [start, end] = part.split('-').map(Number);
                if (start && end && end >= start) count += (end - start + 1);
            } else {
                if (part !== '') count++;
            }
        });

        return count;
    }

    function calculateTotal() {
        const copies = parseInt(document.getElementById('printCopies').value) || 1;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;

        let pagesToPrint = current.pages;

        if (pageOption === 'custom') {
            const input = document.getElementById('printPageRange').value;
            const c = countCustomPages(input);
            pagesToPrint = c > 0 ? c : 0;
        }

        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const total = pagesToPrint * copies * pricePerSheet;

        document.getElementById('displayPricePerSheet').innerText = formatRp(pricePerSheet);
        document.getElementById('displayCalculation').innerText = `${pagesToPrint} Hal x ${copies} Copy`;
        document.getElementById('displayTotalPrice').innerText = formatRp(total);

        return { total, pagesToPrint, copies, colorMode, pricePerSheet };
    }

    document.addEventListener('change', function(e) {
        if (e.target.name === 'colorMode') calculateTotal();
        if (e.target.name === 'pageOption') togglePageInput();
    });

    const rangeInput = document.getElementById('printPageRange');
    if (rangeInput) rangeInput.addEventListener('input', calculateTotal);

    // ========= PAYMENT FLOW =========
    function goToPayment() {
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;

        // hitung total
        const calc = calculateTotal();

        // validasi custom page
        let pageRange = null;
        if (pageOption === 'custom') {
            pageRange = document.getElementById('printPageRange').value;
            if (!pageRange) {
                alert("Harap isi rentang halaman.");
                return;
            }
            if (calc.pagesToPrint <= 0) {
                alert("Rentang halaman tidak valid.");
                return;
            }
        }

        // build payload buat transaksi (pending)
        pendingConfig = {
            file_id: current.fileId,
            copies: calc.copies,
            color_mode: calc.colorMode,
            paper_size: document.getElementById('printPaperSize').value,
            page_range: pageRange,
            page_count: (pageOption === 'custom') ? calc.pagesToPrint : current.pages,
            amount: calc.total
        };

        // render payment panel
        panelTitle.innerText = 'Pembayaran';
        panelSubTitle.innerText = 'Transfer sesuai nominal, lalu klik “Saya sudah transfer”';
        document.getElementById('payAmount').innerText = formatRp(pendingConfig.amount);

        showOnly(panelPayment);
    }

    function backToConfig() {
        panelTitle.innerText = 'Konfigurasi';
        panelSubTitle.innerText = 'Atur cetak & lanjut pembayaran';
        showOnly(panelConfig);
        calculateTotal();
    }

    async function submitManualTransaction(e) {
        const btn = e.currentTarget;
        const old = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = 'Memproses...';
        btn.classList.add('opacity-75');

        try {
            const res = await fetch(ENDPOINT_TX_STORE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(pendingConfig)
            });

            let data = {};
            try { data = await res.json(); } catch (_) {}

            if (!res.ok || data.status !== 'success') {
                throw new Error(data.message || 'Gagal membuat transaksi.');
            }

            // sukses -> masuk waiting
            current.mode = 'pending';
            current.txStatus = 'pending';
            current.orderId = data.order_id;

            panelTitle.innerText = 'Status Transaksi';
            panelSubTitle.innerText = 'Menunggu konfirmasi admin';
            document.getElementById('waitingOrderId').innerText = current.orderId || '-';

            showOnly(panelWaiting);

        } catch (err) {
            console.error(err);
            alert("Gagal: " + (err.message || "Terjadi kesalahan server"));
            btn.disabled = false;
            btn.innerHTML = old;
            btn.classList.remove('opacity-75');
        }
    }

    // ========= PAID / PRINT FLOW =========
    function renderPaidPanel() {
        document.getElementById('fileNamePaid').innerText = current.fileName;
        document.getElementById('fileMetaPaid').innerText = `Tipe: ${current.fileType} • Total halaman file: ${current.pages}`;
        document.getElementById('paidOrderId').innerText = current.orderId || '-';

        // config terkunci diambil dari DB (print_config)
        const cfg = current.paidConfig || {};
        const paper = cfg.paper_size || 'A4';
        const color = cfg.color_mode || '-';
        const copies = cfg.copies ?? '-';
        const pageCount = cfg.page_count ?? '-';

        document.getElementById('lockedPaper').innerText = paper;
        document.getElementById('lockedColor').innerText = (color === 'bw') ? 'Hitam Putih' : (color === 'color' ? 'Berwarna' : color);
        document.getElementById('lockedCopies').innerText = copies;
        document.getElementById('lockedPages').innerText = pageCount;

        // total ambil dari kolom amount kalau ada, fallback hitung
        let total = current.amount;
        if (total == null) {
            const price = (color === 'bw') ? PRICE_BW : PRICE_COLOR;
            total = (Number(pageCount || 0) * Number(copies || 0) * price);
        }
        document.getElementById('lockedTotal').innerText = formatRp(total);
    }

    async function sendPrintJob(e) {
        document.getElementById('printingOrderId').innerText = current.orderId || '-';
        panelTitle.innerText = 'Mencetak...';
        panelSubTitle.innerText = 'Mengirim job print ke server print';
        showOnly(panelPrinting);

        // payload: kirim tx_id + file_id (untuk kompatibilitas)
        // config yang dikirim juga dari DB (paid_config), bukan dari input user
        const cfg = current.paidConfig || {};
        const payload = {
            transaction_id: current.txId,
            id: current.fileId,
            copies: cfg.copies ?? 1,
            color_mode: cfg.color_mode ?? 'bw',
            paper_size: cfg.paper_size ?? 'A4',
            page_range: cfg.page_range ?? null,
        };

        try {
            const res = await fetch(ENDPOINT_PRINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            let data = {};
            try { data = await res.json(); } catch (_) {}

            if (!res.ok || (data.status && data.status !== 'success')) {
                throw new Error(data.message || 'Gagal konek ke server print.');
            }

            alert(data.message || 'Perintah print dikirim.');
            closePrintModal(true);

        } catch (err) {
            console.error(err);
            alert(err.message || 'Gagal konek ke server print.');

            panelTitle.innerText = 'Siap Cetak';
            panelSubTitle.innerText = 'Konfigurasi terkunci sesuai pembayaran';
            renderPaidPanel();
            showOnly(panelPaid);
        }
    }

    // ========= DELETE FLOW =========
    function openDeleteModal(btn) {
        selectedDeleteForm = btn.closest('form');
        document.getElementById('deleteModalTitle').textContent = 'Hapus File?';
        document.getElementById('deleteModalMessage').textContent = 'File akan dihapus permanen.';

        const m = document.getElementById('deleteModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeDeleteModal() {
        const m = document.getElementById('deleteModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        selectedDeleteForm = null;
    }

    function confirmDelete() {
        if (selectedDeleteForm) selectedDeleteForm.submit();
    }

    // ========= QR MODAL =========
    function openQrModal() {
        const m = document.getElementById('qrModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeQrModal() {
        const m = document.getElementById('qrModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    // ========= MULTI SELECT DELETE (UI bawah) =========
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const fileCounter = document.getElementById('fileCounter');
    const deleteAllBtn = document.getElementById('deleteAllBtn');

    function updateSelection() {
        const checkedCount = [...rowChecks].filter(cb => cb.checked).length;

        if (checkedCount > 1) {
            fileCounter.textContent = `${checkedCount} file dipilih`;
            deleteAllBtn.classList.remove('hidden');
        } else if (checkedCount === 1) {
            fileCounter.textContent = '1 file dipilih';
            deleteAllBtn.classList.add('hidden');
        } else {
            fileCounter.textContent = '0 file dipilih';
            deleteAllBtn.classList.add('hidden');
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rowChecks.forEach(cb => cb.checked = checkAll.checked);
            updateSelection();
        });
    }

    rowChecks.forEach(cb => {
        cb.addEventListener('change', function () {
            const allChecked = [...rowChecks].every(c => c.checked);
            if (checkAll) checkAll.checked = allChecked;
            updateSelection();
        });
    });

    function deleteSelectedFiles() {
        const checkedBoxes = [...rowChecks].filter(cb => cb.checked);
        if (checkedBoxes.length === 0) return;

        // gunakan modal yang sama, tapi submit ke route destroy-multiple
        document.getElementById('deleteModalTitle').textContent = 'Hapus File?';
        document.getElementById('deleteModalMessage').textContent = `${checkedBoxes.length} file akan dihapus permanen.`;

        selectedDeleteForm = null; // supaya confirmDelete pakai form multi
        window.__multiDeleteIds = checkedBoxes.map(cb => cb.dataset.fileId);

        const m = document.getElementById('deleteModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    // override confirmDelete untuk multi-delete juga (tanpa ubah flow single delete)
    const _confirmDeleteOriginal = confirmDelete;
    confirmDelete = function () {
        if (window.__multiDeleteIds && window.__multiDeleteIds.length > 0) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = @json(route("station.destroy-multiple", [], false));

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = CSRF_TOKEN;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            window.__multiDeleteIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'file_ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });

            document.body.appendChild(form);
            form.submit();
            return;
        }
        _confirmDeleteOriginal();
    };
</script>

@endsection
