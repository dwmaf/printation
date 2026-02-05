@extends('layouts.app') @section('child')

<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<!-- <div class="h-screen p-8">
    <div class="h-full flex flex-col justify-center items-center bg-[#FAFAFA] gap-8 rounded-2xl">
        <h1 class="text-4xl font-black">
            Print Station
        </h1>

        @if($files->isEmpty())
            <div class="bg-white py-8 rounded-2xl shadow-2xl flex flex-col items-center text-center max-w-lg">
                <p class="text-gray-400 mb-8">Scan QR di bawah ini untuk mulai upload file.</p>
                
                <div class="relative w-80% bg-white mb-8 overflow-hidden [&>svg]:w-full [&>svg]:h-full [&>svg]:scale-110">
                    {!! $qrCode !!}
                </div>

                <div class="flex w-80% items-center space-x-3 bg-[#ECECEC] px-6 py-3 rounded-full">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                    <span class="text-sm font-medium">Menunggu upload file...</span>
                </div>
            </div>

        @else
            <div class="w-full max-w-4xl">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 p-2 rounded-lg">
                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.5 9H13.5V3H4.5V21H19.5V9ZM18.8789 7.5L15 3.62109V7.5H18.8789ZM3.75 1.5H15L21 7.5V21.75C21 21.9489 20.921 22.1397 20.7803 22.2803C20.6397 22.421 20.4489 22.5 20.25 22.5H3.75C3.55109 22.5 3.36032 22.421 3.21967 22.2803C3.07902 22.1397 3 21.9489 3 21.75V2.25C3 2.05109 3.07902 1.86032 3.21967 1.71967C3.36032 1.57902 3.55109 1.5 3.75 1.5ZM7.5 12H16.5V13.5H7.5V12ZM7.5 7.5H11.25V9H7.5V7.5ZM7.5 16.5H16.5V18H7.5V16.5Z" fill="white"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold">Dokumen diterima!</h2>
                            <p class="text-xs text-gray-400">Silakan pilih file untuk dicetak</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($files as $file)
                
                @php
                    $fileUrl = asset('storage/' . $file->filename);
                    $isPdf   = $file->type == 'PDF';
                    $filePath = storage_path('app/public/' . $file->filename);
        
                    // Default 1 halaman
                    $pageCount = 1;

                    // Jika file PDF ada, hitung halamannya
                    if ($isPdf && file_exists($filePath)) {
                        $content = @file_get_contents($filePath);
                        if ($content) {
                            // Hitung berapa kali kata "/Type /Page" muncul di dalam file PDF
                            $count = preg_match_all("/\/Type\s*\/Page[^s]/", $content, $matches);
                            if ($count > 0) {
                                $pageCount = $count;
                            }
                        }
                    }
                @endphp

                <div class="bg-[#f3f3f3] hover:bg-[#d4d4d4] p-5 rounded-xl flex justify-between items-center transition-colors group">
                    
                    <div class="flex items-center space-x-4 overflow-hidden">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-bold shadow-inner shrink-0
                            {{ $isPdf ? 'bg-red-600 text-white' : 'bg-purple-600 text-white' }}">
                            {{ $file->type }}
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-lg font-bold  truncate pr-4">
                                {{ $file->original_name ?? $file->filename }}
                            </h3>
                            <p class="text-xs text-gray-400">
                                {{ $file->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end items-center space-x-3 shrink-0">
                        <button onclick="openPrintModal('{{ $file->id }}', '{{ $fileUrl }}', '{{ $pageCount }}')"
                            class="bg-white text-gray-900 hover:bg-blue-500 hover:text-white px-6 py-3 rounded-lg font-bold shadow-lg transition-all flex items-center group cursor-pointer">
                            <span class="mr-2">PRINT</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </button>

                        <form action="{{ route('station.destroy', $file->id) }}" method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?');">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" class="bg-gray-200 hover:bg-red-600 text-black p-3 rounded-lg shadow-lg font-bold transition-colors flex items-center justify-center tooltip cursor-pointer" title="Hapus File">
                                
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>

                    </div>
                </div>
                @endforeach
            </div>

            </div>

        @endif
    </div>
</div>

<div id="printModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity opacity-0" style="transition: opacity 0.3s ease-out;">
    
    <div class="bg-white rounded-2xl overflow-hidden w-full max-w-6xl h-[85vh] flex shadow-2xl scale-95 transition-transform" id="modalContent" style="transition: transform 0.3s ease-out;">
        
        {{-- Bagian Kiri: Preview --}}
        <div class="w-2/3 bg-gray-200 relative flex items-center justify-center border-r border-gray-300">
            <div id="loadingSpinner" class="absolute flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mb-3"></div>
                <p class="text-gray-500 font-bold">Memuat Preview...</p>
            </div>

            <iframe id="previewFrame" class="w-full h-full relative z-10" src=""></iframe>
            
            <div class="absolute inset-0 z-20 pointer-events-none"></div>
        </div>

        {{-- Bagian Kanan: Konfigurasi Print --}}
        <div class="w-1/3 bg-gray-50 flex flex-col h-full overflow-hidden relative">
            <div class="flex justify-between items-start p-6 pb-2 shrink-0 bg-gray-50 z-10">
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-wide">Konfigurasi</h2>
                <button onclick="closePrintModal()" class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 pt-2">
                <div class="space-y-5">
                    {{-- 1. Paper Size --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ukuran Kertas</label>
                        <select id="printPaperSize" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 font-bold">
                            <option value="A4" selected>A4</option>
                            <option value="Legal">Legal / F4</option>
                        </select>
                    </div>

                    {{-- 2. Pages --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rentang Halaman</label>
                        
                        <div class="flex items-center mb-3 space-x-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="all" checked onchange="togglePageInput()" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-900">Semua</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="custom" onchange="togglePageInput()" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-900">Custom</span>
                            </label>
                        </div>

                        <div id="customPageInputDiv" class="hidden">
                            <input id="printPageRange" type="text" placeholder="Contoh: 1-5, 8, 11-13" 
                                class="w-full text-sm font-bold border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <p class="text-[10px] text-gray-400 mt-1">Gunakan tanda hubung (-) untuk rentang dan koma (,) untuk halaman acak.</p>
                        </div>
                    </div>

                    {{-- 3. Copies --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Copy</label>
                        <div class="flex items-center">
                            <button onclick="adjustCopies(-1)" class="w-10 h-10 bg-gray-100 rounded-l-lg hover:bg-gray-200 font-bold">-</button>
                            <input id="printCopies" type="number" value="1" min="1" readonly
                                class="w-full text-center text-xl font-bold text-gray-800 border-y border-x-0 border-gray-200 h-10 focus:ring-0">
                            <button onclick="adjustCopies(1)" class="w-10 h-10 bg-gray-100 rounded-r-lg hover:bg-gray-200 font-bold">+</button>
                        </div>
                    </div>

                    {{-- 4. Colour Mode --}}
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

                    {{-- Rincian Harga --}}
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mt-6">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                            <span>Harga per lembar</span>
                            <span id="displayPricePerSheet" class="font-bold">Rp 500</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span>Kalkulasi</span>
                            <span id="displayCalculation">1 Hal x 1 Copy</span>
                        </div>

                        <div class="border-t border-blue-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-blue-900 text-lg">TOTAL BAYAR</span>
                            <span id="displayTotalPrice" class="font-black text-2xl text-blue-600">Rp 500</span>
                        </div>
                    </div>
                    
                    <div class="h-4"></div>
                </div>
            </div>

            <div class="p-6 pt-4 shrink-0 bg-gray-50 border-t border-gray-200 z-10 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                <div class="space-y-3">
                    <button onclick="confirmPrint()" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-black text-lg shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center group">
                        <svg class="w-6 h-6 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        CETAK SEKARANG
                    </button>
                    <button onclick="closePrintModal()" class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('printModal');
    const modalContent = document.getElementById('modalContent');
    const previewFrame = document.getElementById('previewFrame');
    const spinner = document.getElementById('loadingSpinner');
    
    // State Variables
    let selectedFileId = null;
    let activePageCount = 1; 
    const PRICE_BW = 500;    
    const PRICE_COLOR = 1000; 

    // Variabel global untuk menyimpan config sementara
    let pendingConfig = {};

    // --- LOGIC BUKA/TUTUP MODAL ---
    function openPrintModal(id, url, pages = 1) {
        selectedFileId = id;
        activePageCount = pages;

        // Reset UI 
        previewFrame.src = ''; 
        spinner.style.display = 'flex';
        
        // Reset Input Values 
        if(document.getElementById('printCopies')) {
            document.getElementById('printCopies').value = 1;
            document.querySelector('input[name="pageOption"][value="all"]').checked = true;
            document.querySelector('input[name="colorMode"][value="color"]').checked = true;
            togglePageInput();
        }

        // Hide toolbar PDF
        previewFrame.src = url + "#toolbar=0&navpanes=0&scrollbar=0&view=Fit";

        calculateTotal();

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        previewFrame.onload = function() {
            spinner.style.display = 'none';
        };
    }

    function closePrintModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            previewFrame.src = '';
            
            if(document.getElementById('qrView')) {
                location.reload(); 
            }
        }, 300);
    }

    // --- LOGIC UI INPUT ---
    function adjustCopies(amount) {
        const input = document.getElementById('printCopies');
        if(!input) return; // Guard clause jika elemen hilang
        let val = parseInt(input.value) + amount;
        if(val < 1) val = 1;
        input.value = val;
        calculateTotal();
    }

    // Event Delegation untuk Radio Button
    document.addEventListener('change', function(e) {
        if(e.target.name === 'colorMode') {
            calculateTotal();
        }
    });

    function calculateTotal() {
        // Cek apakah elemen ada 
        if(!document.getElementById('printCopies')) return;

        const copies = parseInt(document.getElementById('printCopies').value) || 1;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;
        
        let pagesToPrint = activePageCount; 

        if (pageOption === 'custom') {
            const input = document.getElementById('printPageRange').value;
            pagesToPrint = countCustomPages(input); 
        }

        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const total = pagesToPrint * copies * pricePerSheet;

        const formatRp = (num) => "Rp " + num.toLocaleString('id-ID');

        document.getElementById('displayPricePerSheet').innerText = formatRp(pricePerSheet);
        document.getElementById('displayCalculation').innerText = `${pagesToPrint} Hal x ${copies} Copy`;
        document.getElementById('displayTotalPrice').innerText = formatRp(total);
        
        return total; // Return nilai total
    }

    function countCustomPages(rangeString) {
        if (!rangeString) return 0; 
        const parts = rangeString.replace(/\s/g, '').split(',');
        let count = 0;
        parts.forEach(part => {
            if (part.includes('-')) {
                const [start, end] = part.split('-').map(Number);
                if (start && end && end >= start) {
                    count += (end - start + 1);
                }
            } else {
                if (part !== '') count++;
            }
        });
        return count === 0 ? 1 : count;
    }

    const rangeInput = document.getElementById('printPageRange');
    if(rangeInput) {
        rangeInput.addEventListener('input', calculateTotal);
    }

    function togglePageInput() {
        if(!document.getElementById('printPageRange')) return;

        const isCustom = document.querySelector('input[name="pageOption"]:checked').value === 'custom';
        const div = document.getElementById('customPageInputDiv');
        const customInput = document.getElementById('printPageRange');

        if(isCustom) {
            div.classList.remove('hidden');
            customInput.focus(); 
        } else {
            div.classList.add('hidden');
            customInput.value = '';
        }
        calculateTotal();
    }

    // --- STEP 1: LOGIC TOMBOL "CETAK SEKARANG" ---
    function confirmPrint() {
        // 1. Ambil Data Config
        const elCopies = document.getElementById('printCopies');
        if(!elCopies) return; 

        const copies = elCopies.value;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const paperSize = document.getElementById('printPaperSize').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;
        
        let pageRange = null;
        let pagesToPrint = activePageCount;

        if (pageOption === 'custom') {
            pageRange = document.getElementById('printPageRange').value;
            if(!pageRange) {
                alert("Harap isi rentang halaman.");
                return;
            }
            pagesToPrint = countCustomPages(pageRange);
        }

        // 2. Hitung Total Final
        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const totalAmount = pagesToPrint * copies * pricePerSheet;

        // 3. Simpan Config ke Variable Global
        pendingConfig = {
            file_id: selectedFileId,
            copies: copies,
            color_mode: colorMode,
            paper_size: paperSize,
            page_range: pageRange,
            page_count: pagesToPrint,
            amount: totalAmount
        };

        // 4. TIMPA PANEL KANAN DENGAN TAMPILAN PEMBAYARAN FULL
        const rightPanel = document.querySelector('#modalContent .w-1\\/3');
        
        // Gunakan API QR Server 
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=TransferRp${totalAmount}`;

        rightPanel.innerHTML = `
            <div id="qrView" class="h-full flex flex-col bg-white animate-fade-in relative">
                
                {{-- Header Sederhana --}}
                <div class="p-6 border-b border-gray-100 flex justify-between items-center shrink-0">
                    <h2 class="text-xl font-black text-gray-800 uppercase tracking-wide">PEMBAYARAN</h2>
                    {{-- Tombol X reload page agar bersih --}}
                    <button onclick="location.reload()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Body Full (Termasuk Tombol) --}}
                <div class="flex-1 overflow-y-auto p-6 flex flex-col items-center text-center custom-scrollbar">
                    
                    <p class="text-gray-500 mb-2 font-medium">Silakan transfer dengan nominal tepat:</p>
                    
                    <div class="bg-blue-600 text-white font-black text-4xl py-4 px-8 rounded-2xl mb-8 shadow-lg shadow-blue-500/30 tracking-wide">
                        Rp ${totalAmount.toLocaleString('id-ID')}
                    </div>

                    <div class="bg-white p-2 border-2 border-gray-200 rounded-2xl mb-6 shadow-sm">
                        <img src="${qrUrl}" class="w-56 h-56 object-contain">
                    </div>

                    <p class="text-xs text-gray-400 mb-8 max-w-xs mx-auto">
                        Scan QRIS di atas menggunakan GoPay, OVO, Dana, atau Mobile Banking Anda.
                    </p>

                    {{-- Spacer agar tombol terdorong ke bawah jika layar tinggi --}}
                    <div class="mt-auto w-full space-y-3">
                        <button onclick="submitManualTransaction()" class="w-full py-4 bg-green-600 hover:bg-green-500 text-white rounded-xl font-bold text-lg shadow-xl shadow-green-500/20 flex items-center justify-center group transition-all transform hover:-translate-y-1">
                            <span>SAYA SUDAH TRANSFER</span>
                            <svg class="w-6 h-6 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                        
                        <button onclick="location.reload()" class="w-full py-3 text-gray-400 hover:text-gray-600 font-bold text-sm cursor-pointer">
                            Kembali / Batal
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // --- STEP 2: KIRIM DATA KE BACKEND ---
    function submitManualTransaction() {
        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;
        
        // Ubah tampilan tombol jadi loading
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
        btn.disabled = true;
        btn.classList.add('opacity-75');

        fetch('{{ route("transaction.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(pendingConfig)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // TAMPILKAN TIKET ANTRIAN (ORDER ID)
                const rightPanel = document.querySelector('#modalContent .w-1\\/3');
                
                rightPanel.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-green-50 animate-fade-in">
                        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6 animate-bounce shadow-sm">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <h2 class="text-3xl font-black text-gray-900 mb-2">BERHASIL!</h2>
                        <p class="text-gray-600 mb-8 font-medium">Pesanan Anda telah diterima.</p>
                        
                        <div class="bg-white border-2 border-dashed border-green-300 p-6 rounded-2xl w-full mb-8 relative shadow-sm">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">KODE ORDER ANDA</p>
                            <div class="text-5xl font-black text-gray-900 tracking-widest select-all">
                                ${data.order_id}
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mb-8 px-4">
                            Silakan lapor ke <strong>Admin / Kasir</strong> dan tunjukkan Kode Order di atas.
                        </p>

                        <button onclick="location.reload()" class="w-full py-4 bg-gray-900 text-white rounded-xl font-bold shadow-lg hover:bg-gray-800 transition-all transform hover:scale-[1.02]">
                            Selesai & Tutup
                        </button>
                    </div>
                `;
            } else {
                alert("Gagal: " + data.message);
                resetBtn();
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan koneksi.");
            resetBtn();
        });

        function resetBtn() {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }
</script> -->


<div class="h-screen py-8 flex flex-col">
    <div class="h-30 flex justify-center items-center gap-4 mb-12">
        <img src="{{ asset('images/placeholder_logo.png') }}">
        <h1 class="text-4xl font-black text-center">Print Station</h1>
    </div>

    <div class="flex-1 w-full px-8">
        @if($files->isEmpty())
            <div class="w-full h-96 flex flex-col items-center justify-center">
                <h2 class="text-2xl font-bold mb-4">Deus Ex-Machina</h2>
                <p class="text-gray-400 mb-8">Scan QR di bawah ini untuk mulai upload file.</p>
                <div class="relative w-80% bg-white mb-8 overflow-hidden [&>svg]:w-full [&>svg]:h-full">
                    {!! $qrCode !!}
                </div>
                <div class="flex items-center space-x-3 bg-[#ECECEC] px-6 py-3 rounded-full">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                    <span class="text-sm font-medium">Menunggu upload file...</span>
                </div>
            </div>
        @else
            <div class="w-full overflow-y-auto custom-scrollbar">
                <div class="mb-8 flex items-center gap-8 h-10">
                    <p id="fileCounter" class="text-lg font-semibold text-gray-700">0 file dipilih</p>
                    <button id="deleteAllBtn" onclick="deleteSelectedFiles()" class="hidden bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-all cursor-pointer">
                        Hapus semua
                    </button>
                </div>
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr class="text-left text-sm font-semibold text-gray-700">
                            <th class="p-3 text-center">
                                <input type="checkbox" id="checkAll" class="w-4 h-4 accent-blue-600 rounded cursor-pointer">
                            </th>
                            <th class="p-3 text-lg text-center">Tipe</th>
                            <th class="p-3 text-lg">Nama File</th>
                            <th class="p-3 text-lg">Diunggah</th>
                            <th class="p-3 text-lg text-center">Aksi</th>
                        </tr>
                    </thead>

                <tbody class="divide-y">
                    @foreach($files as $file)

                    @php
                        $fileUrl  = asset('storage/' . $file->filename);
                        
                        $type = strtoupper($file->type);
                    @endphp

                    <tr class="hover:bg-gray-50 transition text-center">
                        <td class="p-3">
                            <input type="checkbox" class="row-check w-4 h-4 accent-blue-600 rounded cursor-pointer" data-file-id="{{ $file->id }}">
                        </td>

                        <td class="p-3 font-bold 
                            {{ $isPdf ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $file->type }}
                        </td>

                        <td class="p-3 truncate max-w-[250px] text-left">
                            {{ $file->original_name ?? $file->filename }}
                        </td>

                        <td class="p-3 text-sm text-gray-500 text-left">
                            {{ $file->created_at->diffForHumans() }}
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center gap-1">
                                <button onclick="openPrintModal('{{ $file->id }}', '{{ $fileUrl }}', '{{ $type }}')"
                                    class="text-gray-900 hover:bg-blue-500 hover:text-white px-3 py-3 rounded-lg font-bold transition-all flex items-center group cursor-pointer tooltip" title="Print">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </button>

                                <form action="{{ route('station.destroy', $file->id) }}" method="POST" class="delete-file">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="button" class="hover:bg-red-600 text-black hover:text-white p-3 rounded-lg font-bold transition-colors flex items-center justify-center tooltip cursor-pointer" title="Hapus File" onclick="openDeleteModal(this)">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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
    </div>
</div>

<div id="printModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity opacity-0" style="transition: opacity 0.3s ease-out;">
    
    <div class="bg-white rounded-2xl overflow-hidden w-full max-w-6xl h-[85vh] flex shadow-2xl scale-95 transition-transform" id="modalContent" style="transition: transform 0.3s ease-out;">
        
        {{-- Bagian Kiri: Preview --}}
        <div class="w-2/3 bg-gray-200 relative flex items-center justify-center border-r border-gray-300">
            <div id="loadingSpinner" class="absolute flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mb-3"></div>
                <p class="text-gray-500 font-bold">Memuat Preview...</p>
            </div>

            <iframe id="previewFrame" class="w-full h-full relative z-10" src=""></iframe>
            
            <div class="absolute inset-0 z-20 pointer-events-none"></div>
        </div>

        {{-- Bagian Kanan: Konfigurasi Print --}}
        <div class="w-1/3 bg-gray-50 flex flex-col h-full overflow-hidden relative">
            <div class="flex justify-between items-start p-6 pb-2 shrink-0 bg-gray-50 z-10">
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-wide">Konfigurasi</h2>
                <!-- <button onclick="closePrintModal()" class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button> -->
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 pt-2">
                <div class="space-y-5">
                    {{-- 1. Paper Size --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ukuran Kertas</label>
                        <select id="printPaperSize" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 font-bold">
                            <option value="A4" selected>A4</option>
                            <option value="Legal">Legal / F4</option>
                        </select>
                    </div>

                    {{-- 2. Pages --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rentang Halaman</label>
                        
                        <div class="flex items-center mb-3 space-x-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="all" checked onchange="togglePageInput()" class="w-4 h-4 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="ml-2 text-sm font-medium text-gray-900">Semua</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="pageOption" value="custom" onchange="togglePageInput()" class="w-4 h-4 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="ml-2 text-sm font-medium text-gray-900">Custom</span>
                            </label>
                        </div>

                        <div id="customPageInputDiv" class="hidden">
                            <input id="printPageRange" type="text" placeholder="Contoh: 1-5, 8, 11-13" 
                                class="w-full text-sm font-bold border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <p class="text-[10px] text-gray-400 mt-1">Gunakan tanda hubung (-) untuk rentang dan koma (,) untuk halaman acak.</p>
                        </div>
                    </div>

                    {{-- 3. Copies --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Copy</label>
                        <div class="flex items-center">
                            <button onclick="adjustCopies(-1)" class="w-10 h-10 bg-gray-100 rounded-l-lg hover:bg-gray-200 font-bold cursor-pointer">-</button>
                            <input id="printCopies" type="number" value="1" min="1" readonly
                                class="w-full text-center text-xl font-bold text-gray-800 border-y border-x-0 border-gray-200 h-10 focus:ring-0">
                            <button onclick="adjustCopies(1)" class="w-10 h-10 bg-gray-100 rounded-r-lg hover:bg-gray-200 font-bold cursor-pointer">+</button>
                        </div>
                    </div>

                    {{-- 4. Colour Mode --}}
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

                    {{-- Rincian Harga --}}
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mt-6">
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                            <span>Harga per lembar</span>
                            <span id="displayPricePerSheet" class="font-bold">Rp 500</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                            <span>Kalkulasi</span>
                            <span id="displayCalculation">1 Hal x 1 Copy</span>
                        </div>

                        <div class="border-t border-blue-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-blue-900 text-lg">TOTAL BAYAR</span>
                            <span id="displayTotalPrice" class="font-black text-2xl text-blue-600">Rp 500</span>
                        </div>
                    </div>
                    
                    <div class="h-4"></div>
                </div>
            </div>

            <div class="p-6 pt-4 shrink-0 bg-gray-50 border-t border-gray-200 z-10 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                <div class="space-y-3">
                    <button id="btnPrintNow" onclick="confirmPrint()" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-black text-lg shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center group cursor-pointer">
                        <svg class="w-6 h-6 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        CETAK SEKARANG
                    </button>
                    <button onclick="closePrintModal()" class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('printModal');
    const modalContent = document.getElementById('modalContent');
    const previewFrame = document.getElementById('previewFrame');
    const spinner = document.getElementById('loadingSpinner');
    
    // State Variables
    let selectedFileId = null;
    let activePageCount = 1; 
    const PRICE_BW = 500;    
    const PRICE_COLOR = 1000; 

    // Variabel global untuk menyimpan config sementara
    let pendingConfig = {};

    // --- LOGIC BUKA/TUTUP MODAL ---
    async function openPrintModal(id, url, type = 'PDF') {
        selectedFileId = id;
        const btnPrint = document.getElementById('btnPrintNow');
        
        // 1. STATE AWAL: RESET UI & MATIKAN TOMBOL
        document.getElementById('displayCalculation').innerHTML = '<span class="animate-pulse text-blue-500 font-bold">Sedang menghitung...</span>';
        document.getElementById('displayTotalPrice').innerText = 'Rp ...';
        
        // Reset variabel halaman sementara
        activePageCount = 1; 

        // Matikan tombol cetak
        if(btnPrint) {
            btnPrint.disabled = true;
            btnPrint.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
            btnPrint.classList.remove('bg-blue-600', 'hover:bg-blue-500', 'shadow-lg');
            btnPrint.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghitung Halaman...`;
        }

        // Reset Input Form ke Default
        if(document.getElementById('printCopies')) {
            document.getElementById('printCopies').value = 1;
            document.querySelector('input[name="pageOption"][value="all"]').checked = true;
            document.querySelector('input[name="colorMode"][value="color"]').checked = true;
            togglePageInput();
        }

        // Reset UI Preview
        previewFrame.src = ''; 
        spinner.style.display = 'flex';

        // Tampilkan Modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);

        // 2. PROSES HITUNG HALAMAN (ASYNC)
        const rangeContainer = document.getElementById('containerPageRange');
        const imageTypes = ['JPG', 'JPEG', 'PNG', 'WEBP'];
        const safeType = (type || '').toUpperCase();
        const isImage = imageTypes.includes(safeType);

        try {
            if (isImage) {
                // GAMBAR: 1 halaman
                if(rangeContainer) rangeContainer.classList.add('hidden');
                activePageCount = 1;
            } else {
                // PDF: Download hitung halaman
                if(rangeContainer) rangeContainer.classList.remove('hidden');
                
                const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer());
                const pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);
                activePageCount = pdfDoc.getPageCount();
            }
        } catch (error) {
            console.error("Gagal baca halaman:", error);
            activePageCount = 1; 
            alert("Gagal mendeteksi halaman otomatis. Default ke 1.");
        }

        // 3. STATE AKHIR: AKTIFKAN TOMBOL & HITUNG HARGA
        calculateTotal(); 

        // Hidupkan kembali tombol cetak
        if(btnPrint) {
            btnPrint.disabled = false;
            btnPrint.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
            btnPrint.classList.add('bg-blue-600', 'hover:bg-blue-500', 'shadow-lg');
            
            // Kembalikan teks & ikon asli
            btnPrint.innerHTML = `
                <svg class="w-6 h-6 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK SEKARANG
            `;
        }

        // 4. Load Preview PDF
        previewFrame.src = url + "#toolbar=0&navpanes=0&scrollbar=0&view=Fit";
        previewFrame.onload = function() {
            spinner.style.display = 'none';
        };
    }

    function closePrintModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            previewFrame.src = '';
            
            if(document.getElementById('qrView')) {
                location.reload(); 
            }
        }, 300);
    }

    // --- LOGIC UI INPUT ---
    function adjustCopies(amount) {
        const input = document.getElementById('printCopies');
        if(!input) return; // Guard clause jika elemen hilang
        let val = parseInt(input.value) + amount;
        if(val < 1) val = 1;
        input.value = val;
        calculateTotal();
    }

    // Event Delegation untuk Radio Button
    document.addEventListener('change', function(e) {
        if(e.target.name === 'colorMode') {
            calculateTotal();
        }
    });

    function calculateTotal() {
        // Cek apakah elemen ada 
        if(!document.getElementById('printCopies')) return;

        const copies = parseInt(document.getElementById('printCopies').value) || 1;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;
        
        let pagesToPrint = activePageCount; 

        if (pageOption === 'custom') {
            const input = document.getElementById('printPageRange').value;
            pagesToPrint = countCustomPages(input); 
        }

        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const total = pagesToPrint * copies * pricePerSheet;

        const formatRp = (num) => "Rp " + num.toLocaleString('id-ID');

        document.getElementById('displayPricePerSheet').innerText = formatRp(pricePerSheet);
        document.getElementById('displayCalculation').innerText = `${pagesToPrint} Hal x ${copies} Copy`;
        document.getElementById('displayTotalPrice').innerText = formatRp(total);
        
        return total; // Return nilai total
    }

    function countCustomPages(rangeString) {
        if (!rangeString) return 0; 
        const parts = rangeString.replace(/\s/g, '').split(',');
        let count = 0;
        parts.forEach(part => {
            if (part.includes('-')) {
                const [start, end] = part.split('-').map(Number);
                if (start && end && end >= start) {
                    count += (end - start + 1);
                }
            } else {
                if (part !== '') count++;
            }
        });
        return count === 0 ? 1 : count;
    }

    const rangeInput = document.getElementById('printPageRange');
    if(rangeInput) {
        rangeInput.addEventListener('input', calculateTotal);
    }

    function togglePageInput() {
        if(!document.getElementById('printPageRange')) return;

        const isCustom = document.querySelector('input[name="pageOption"]:checked').value === 'custom';
        const div = document.getElementById('customPageInputDiv');
        const customInput = document.getElementById('printPageRange');

        if(isCustom) {
            div.classList.remove('hidden');
            customInput.focus(); 
        } else {
            div.classList.add('hidden');
            customInput.value = '';
        }
        calculateTotal();
    }

    // --- STEP 1: LOGIC TOMBOL "CETAK SEKARANG" ---
    function confirmPrint() {
        // 1. Ambil Data Config
        const elCopies = document.getElementById('printCopies');
        if(!elCopies) return; 

        const copies = elCopies.value;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const paperSize = document.getElementById('printPaperSize').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;
        
        let pageRange = null;
        let pagesToPrint = activePageCount;

        if (pageOption === 'custom') {
            pageRange = document.getElementById('printPageRange').value;
            if(!pageRange) {
                alert("Harap isi rentang halaman.");
                return;
            }
            pagesToPrint = countCustomPages(pageRange);
        }

        // 2. Hitung Total Final
        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const totalAmount = pagesToPrint * copies * pricePerSheet;

        // 3. Simpan Config ke Variable Global
        pendingConfig = {
            file_id: selectedFileId,
            copies: copies,
            color_mode: colorMode,
            paper_size: paperSize,
            page_range: pageRange,
            page_count: pagesToPrint,
            amount: totalAmount
        };

        // 4. TIMPA PANEL KANAN DENGAN TAMPILAN PEMBAYARAN FULL
        const rightPanel = document.querySelector('#modalContent .w-1\\/3');
        
        // Gunakan API QR Server 
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=TransferRp${totalAmount}`;

        rightPanel.innerHTML = `
            <div id="qrView" class="h-full flex flex-col bg-white animate-fade-in relative">
                
                {{-- Header Sederhana --}}
                <div class="p-6 border-b border-gray-100 flex justify-between items-center shrink-0">
                    <h2 class="text-xl font-black text-gray-800 tracking-wide">PEMBAYARAN</h2>
                    
                </div>

                {{-- Body Full (Termasuk Tombol) --}}
                <div class="flex-1 overflow-y-auto p-6 flex flex-col items-center text-center custom-scrollbar">
                    
                    <div class="bg-blue-600 text-white font-black text-4xl py-4 px-8 rounded-2xl mb-8 shadow-lg shadow-blue-500/30 tracking-wide">
                        Rp ${totalAmount.toLocaleString('id-ID')}
                    </div>

                    <div class="bg-white p-2 border-2 border-gray-200 rounded-2xl mb-6 shadow-sm">
                        <img src="${qrUrl}" class="w-56 h-56 object-contain">
                    </div>

                    <p class="text-xs text-gray-400 mb-2 max-w-xs mx-auto">
                        Scan QRIS di atas menggunakan GoPay, OVO, Dana, atau Mobile Banking Anda.
                    </p>

                    {{-- Spacer agar tombol terdorong ke bawah jika layar tinggi --}}
                    <div class="mt-auto w-full space-y-3">
                        <button onclick="submitManualTransaction()" class="w-full py-4 bg-green-600 hover:bg-green-500 text-white rounded-xl font-bold text-lg shadow-xl shadow-green-500/20 flex items-center justify-center group transition-all  cursor-pointer">
                            <span>SAYA SUDAH TRANSFER</span>
                            <svg class="w-6 h-6 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                        
                        <button onclick="backToConfig()" class="w-full py-3 text-gray-400 hover:text-gray-600 font-bold text-sm cursor-pointer">
                            Kembali / Batal
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // --- STEP 2: KIRIM DATA KE BACKEND ---
    function submitManualTransaction() {
        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;
        
        // Ubah tampilan tombol jadi loading
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-pointer');

        fetch('{{ route("transaction.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(pendingConfig)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // TAMPILKAN TIKET ANTRIAN (ORDER ID)
                const rightPanel = document.querySelector('#modalContent .w-1\\/3');
                
                rightPanel.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-green-50 animate-fade-in">
                        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6 animate-bounce shadow-sm">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <h2 class="text-3xl font-black text-gray-900 mb-2">BERHASIL!</h2>
                        <p class="text-gray-600 mb-8 font-medium">Pesanan Anda telah diterima.</p>
                        
                        <div class="bg-white border-2 border-dashed border-green-300 p-6 rounded-2xl w-full mb-8 relative shadow-sm">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">KODE ORDER ANDA</p>
                            <div class="text-5xl font-black text-gray-900 tracking-widest select-all">
                                ${data.order_id}
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mb-8 px-4">
                            Silakan lapor ke <strong>Admin / Kasir</strong> dan tunjukkan Kode Order di atas.
                        </p>

                        <button onclick="location.reload()" class="w-full py-4 bg-gray-900 text-white rounded-xl font-bold shadow-lg hover:bg-gray-800 transition-all transform hover:scale-[1.02] cursor-pointer">
                            Selesai
                    </div>
                `;
            } else {
                alert("Gagal: " + data.message);
                resetBtn();
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan koneksi.");
            resetBtn();
        });

        function resetBtn() {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-75');
        }
    }

    // Pencet Kembali / Batal bakal balik ke konfigurasi
    let originalRightPanelHTML = '';
    const rightPanel = document.querySelector('#modalContent .w-1\\/3');

    if (!originalRightPanelHTML) {
        originalRightPanelHTML = rightPanel.innerHTML;
    }

    function backToConfig() {
        const rightPanel = document.querySelector('#modalContent .w-1\\/3');
        rightPanel.innerHTML = originalRightPanelHTML;

        // Rebind event listener yang hilang
        calculateTotal();
    }

    // Buat checkbox bisa centang semua
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const fileCounter = document.getElementById('fileCounter');
    const deleteAllBtn = document.getElementById('deleteAllBtn');

    // Update counter dan button visibility
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

    checkAll.addEventListener('change', function () {
        rowChecks.forEach(cb => {
            cb.checked = checkAll.checked;
        });
        updateSelection();
    });

    /* ------------------ */
    // Konfirmasi hapus file
    let selectedForm = null;
    let isMultipleDelete = false;
    let selectedFileIds = [];
    
    function openDeleteModal(btn) {
        selectedForm = btn.closest('form');
        isMultipleDelete = false;
        
        document.getElementById('deleteModalTitle').textContent = 'Hapus File?';
        document.getElementById('deleteModalMessage').textContent = 'File akan dihapus permanen.';
        
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        selectedForm = null;
        isMultipleDelete = false;
        selectedFileIds = [];
    }

    function confirmDelete() {
        if (isMultipleDelete) {
            // Handle multiple delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("station.destroy-multiple") }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            selectedFileIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'file_ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });
            
            document.body.appendChild(form);
            form.submit();
        } else if (selectedForm) {
            // Handle single delete
            selectedForm.submit();
        }
    }

    // Kalau salah satu unchecked, yg lain juga
    rowChecks.forEach(cb => {
        cb.addEventListener('change', function () {
            const allChecked = [...rowChecks].every(c => c.checked);
            checkAll.checked = allChecked;
            updateSelection();
        });
    });

    // Fungsi untuk menghapus semua file yang dipilih
    function deleteSelectedFiles() {
        const checkedBoxes = [...rowChecks].filter(cb => cb.checked);
        
        if (checkedBoxes.length === 0) {
            return;
        }

        // Set flag dan data untuk multiple delete
        isMultipleDelete = true;
        selectedFileIds = checkedBoxes.map(cb => cb.dataset.fileId);
        
        // Update modal content
        document.getElementById('deleteModalTitle').textContent = 'Hapus File?';
        document.getElementById('deleteModalMessage').textContent = `${checkedBoxes.length} file akan dihapus permanen.`;
        
        // Open modal
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>