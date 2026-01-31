@extends('layouts.app')

@section('child')

<div class="min-h-screen bg-gray-900 text-white font-sans flex flex-col items-center p-6 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2"></div>

    <div class="mb-10 text-center z-10">
        <h1 class="text-4xl font-black text-white tracking-widest uppercase drop-shadow-lg">
            Print Station
        </h1>
    </div>

    @if($files->isEmpty())
        <div class="bg-gray-700 p-10 rounded-3xl shadow-2xl flex flex-col items-center text-center max-w-lg w-full">
            <p class="text-gray-400 mb-8">Scan QR di bawah ini untuk mulai upload file.</p>
            
            <div class="p-4 rounded-2xl shadow-lg w-64 h-64 flex items-center justify-center mb-8 relative group">
                <div class="relative w-full h-full bg-white">
                    {!! $qrCode !!}
                </div>
            </div>

            <div class="flex items-center space-x-3 bg-gray-900/50 px-6 py-3 rounded-full border border-gray-700">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                <span class="text-sm font-medium text-gray-300">Menunggu Upload File...</span>
            </div>
        </div>

    @else
        <div class="w-full max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.5 9H13.5V3H4.5V21H19.5V9ZM18.8789 7.5L15 3.62109V7.5H18.8789ZM3.75 1.5H15L21 7.5V21.75C21 21.9489 20.921 22.1397 20.7803 22.2803C20.6397 22.421 20.4489 22.5 20.25 22.5H3.75C3.55109 22.5 3.36032 22.421 3.21967 22.2803C3.07902 22.1397 3 21.9489 3 21.75V2.25C3 2.05109 3.07902 1.86032 3.21967 1.71967C3.36032 1.57902 3.55109 1.5 3.75 1.5ZM7.5 12H16.5V13.5H7.5V12ZM7.5 7.5H11.25V9H7.5V7.5ZM7.5 16.5H16.5V18H7.5V16.5Z" fill="black"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white">Dokumen Diterima</h2>
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

            <div class="bg-gray-800 hover:bg-gray-700 border border-gray-700 p-5 rounded-xl flex justify-between items-center shadow-lg transition-colors group">
                
                <div class="flex items-center space-x-4 overflow-hidden">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-bold shadow-inner shrink-0
                        {{ $isPdf ? 'bg-red-600 text-white' : 'bg-purple-600 text-white' }}">
                        {{ $file->type }}
                    </div>

                    <div class="min-w-0">
                        <h3 class="text-lg font-bold text-white truncate pr-4">
                            {{ $file->original_name ?? $file->filename }}
                        </h3>
                        <p class="text-xs text-gray-400">
                            {{ $file->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end items-center space-x-3 shrink-0">
                    <button onclick="openPrintModal('{{ $file->id }}', '{{ $fileUrl }}', '{{ $pageCount }}')"
                        class="bg-white text-gray-900 hover:bg-blue-500 hover:text-white px-6 py-3 rounded-lg font-bold shadow-lg transition-all flex items-center group">
                        <span class="mr-2">PRINT</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </button>

                    <form action="{{ route('station.destroy', $file->id) }}" method="POST" 
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?');">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="bg-gray-700 hover:bg-red-600 text-white p-3 rounded-lg shadow-lg font-bold transition-colors flex items-center justify-center tooltip" title="Hapus File">
                            
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
        <div class="w-1/3 bg-gray-50 p-6 flex flex-col h-full overflow-y-auto custom-scrollbar">
            
            <div class="flex justify-between items-start mb-6 shrink-0">
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-wide">Konfigurasi</h2>
                <button onclick="closePrintModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="space-y-5 flex-1">
                
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

                    {{-- Input Custom --}}
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


            {{-- Tombol Aksi --}}
            <div class="space-y-3 mt-6 shrink-0">
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

<script>
    const modal = document.getElementById('printModal');
    const modalContent = document.getElementById('modalContent');
    const previewFrame = document.getElementById('previewFrame');
    const spinner = document.getElementById('loadingSpinner');
    let selectedFileId = null;

    let activePageCount = 1; 
    const PRICE_BW = 500;    // Harga Hitam Putih
    const PRICE_COLOR = 1000; // Harga Berwarna

    // --- LOGIC BUKA/TUTUP MODAL ---
    function openPrintModal(id, url, pages = 1) {
        selectedFileId = id;
        activePageCount = pages;

        // Reset UI
        previewFrame.src = ''; 
        spinner.style.display = 'flex';
        document.getElementById('printCopies').value = 1;
        
        // Reset Page Option ke 'All'
        document.querySelector('input[name="pageOption"][value="all"]').checked = true;

        document.querySelector('input[name="colorMode"][value="color"]').checked = true;

        togglePageInput();

        
        // 1. Untuk sembunyikan toolbar PDF viewer di iframe
        previewFrame.src = url + "#toolbar=0&navpanes=0&scrollbar=0";

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
        }, 300);
    }

    // --- LOGIC UI INPUT ---
    function adjustCopies(amount) {
        const input = document.getElementById('printCopies');
        let val = parseInt(input.value) + amount;
        if(val < 1) val = 1;
        input.value = val;

        calculateTotal();
    }

    document.querySelectorAll('input[name="colorMode"]').forEach(radio => {
        radio.addEventListener('change', calculateTotal);
    });

    function calculateTotal() {
        // 1. Ambil Data Dasar
        const copies = parseInt(document.getElementById('printCopies').value) || 1;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const pageOption = document.querySelector('input[name="pageOption"]:checked').value;
        
        // 2. Tentukan Jumlah Halaman yang Akan Dicetak
        let pagesToPrint = activePageCount; // Default: Total semua halaman file

        // Jika pilih Custom, hitung manual inputnya
        if (pageOption === 'custom') {
            const input = document.getElementById('printPageRange').value;
            pagesToPrint = countCustomPages(input); 
        }

        // 3. Hitung Harga
        const pricePerSheet = (colorMode === 'bw') ? PRICE_BW : PRICE_COLOR;
        const total = pagesToPrint * copies * pricePerSheet;

        // 4. Update Tampilan
        const formatRp = (num) => "Rp " + num.toLocaleString('id-ID');

        document.getElementById('displayPricePerSheet').innerText = formatRp(pricePerSheet);
        document.getElementById('displayCalculation').innerText = `${pagesToPrint} Hal x ${copies} Copy`;
        document.getElementById('displayTotalPrice').innerText = formatRp(total);
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

    document.getElementById('printPageRange').addEventListener('input', calculateTotal);

    function togglePageInput() {
        // 1. Cek apakah user pilih Custom
        const isCustom = document.querySelector('input[name="pageOption"]:checked').value === 'custom';
        const div = document.getElementById('customPageInputDiv');
        const customInput = document.getElementById('printPageRange');

        // 2. Tampilkan/Sembunyikan Input Box
        if(isCustom) {
            div.classList.remove('hidden');
            customInput.focus(); 
        } else {
            div.classList.add('hidden');
            customInput.value = '';
        }

        calculateTotal();
    }

    // --- LOGIC KIRIM KE SERVER ---
    function confirmPrint() {
        // Ambil Data
        const copies = document.getElementById('printCopies').value;
        const colorMode = document.querySelector('input[name="colorMode"]:checked').value;
        const paperSize = document.getElementById('printPaperSize').value;
        
        // Cek Page Range
        let pageRange = null;
        if (document.querySelector('input[name="pageOption"]:checked').value === 'custom') {
            pageRange = document.getElementById('printPageRange').value;
            if(!pageRange) {
                alert("Harap isi rentang halaman (contoh: 1-3) atau pilih 'Semua'.");
                return;
            }
        }

        // Efek Loading Tombol
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Memproses...';
        btn.disabled = true;

        fetch('{{ route("process.print") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: selectedFileId,
                copies: copies,
                color_mode: colorMode,
                paper_size: paperSize,
                page_range: pageRange
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                alert("✅ BERHASIL! Dokumen dikirim ke printer.");
                closePrintModal();
            } else {
                alert("❌ ERROR: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Gagal menghubungi server.");
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>