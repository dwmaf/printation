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
            
            {{-- 1. Definisikan URL dan Tipe File di awal --}}
            @php
                $fileUrl = asset('storage/' . $file->filename);
                $isPdf   = $file->type == 'PDF';
                // Anggap selain PDF adalah Gambar (karena DOC sudah dilupakan)
            @endphp

            <div class="bg-gray-800 hover:bg-gray-700 border border-gray-700 p-5 rounded-xl flex justify-between items-center shadow-lg transition-colors group">
                
                <div class="flex items-center space-x-4 overflow-hidden">
                    {{-- 2. Logika Warna Icon: Merah untuk PDF, Ungu untuk Gambar --}}
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
                    <button onclick="openPrintModal('{{ $fileUrl }}')"
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

{{-- MODAL BARU: LEBIH BESAR & ADA PREVIEW --}}
<div id="printModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity opacity-0" style="transition: opacity 0.3s ease-out;">
    
    <div class="bg-white rounded-2xl overflow-hidden w-full max-w-6xl h-[85vh] flex shadow-2xl scale-95 transition-transform" id="modalContent" style="transition: transform 0.3s ease-out;">
        
        {{-- BAGIAN KIRI: PREVIEW DOKUMEN --}}
        <div class="w-2/3 bg-gray-200 relative flex items-center justify-center border-r border-gray-300">
            {{-- Loading Spinner (Muncul saat file dimuat) --}}
            <div id="loadingSpinner" class="absolute flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mb-3"></div>
                <p class="text-gray-500 font-bold">Memuat Preview...</p>
            </div>

            {{-- Iframe Preview --}}
            <iframe id="previewFrame" class="w-full h-full relative z-10" src=""></iframe>
        </div>

        {{-- BAGIAN KANAN: KONFIGURASI --}}
        <div class="w-1/3 bg-gray-50 p-8 flex flex-col justify-between">
            
            <div>
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-wide">Konfigurasi</h2>
                    <button onclick="closePrintModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    {{-- Input Copy --}}
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-sm font-bold text-gray-500 uppercase mb-2">Jumlah Copy</label>
                        <div class="flex items-center">
                            <input id="printCopies" type="number" value="1" min="1"
                                class="w-full text-3xl font-bold text-gray-800 border-none focus:ring-0 p-0" placeholder="1">
                            <span class="text-gray-400 font-medium">Lembar</span>
                        </div>
                    </div>

                    {{-- Input Grayscale --}}
                    <label class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:border-blue-400 transition-colors">
                        <span class="font-bold text-gray-700">Mode Hitam Putih</span>
                        <input id="printGrayscale" type="checkbox" class="w-6 h-6 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                    </label>

                </div>
            </div>

            {{-- Action Buttons --}}
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


<script>
    const modal = document.getElementById('printModal');
    const modalContent = document.getElementById('modalContent');
    const previewFrame = document.getElementById('previewFrame');
    const spinner = document.getElementById('loadingSpinner');

    function openPrintModal(url) {
        // 1. Reset State
        previewFrame.src = ''; 
        spinner.style.display = 'flex'; // Munculkan loading
        
        // 2. Set URL ke Iframe (Ini yang bikin preview muncul)
        previewFrame.src = url;

        // 3. Tampilkan Modal dengan Animasi
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Timeout kecil biar animasi transisi jalan halus
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);

        // 4. Hilangkan Spinner kalau iframe sudah selesai loading
        previewFrame.onload = function() {
            spinner.style.display = 'none';
        };
    }

    function closePrintModal() {
        // Animasi Tutup
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            previewFrame.src = ''; // Bersihkan memori iframe
        }, 300); // Sesuaikan dengan durasi transition CSS
    }

    function confirmPrint() {
        // Karena kita pakai window.print() browser,
        // Kita tidak bisa melempar nilai 'copies' & 'grayscale' ke dialog sistem secara paksa.
        // Jadi kita langsung print iframe yang sedang dilihat user.
        
        try {
            previewFrame.contentWindow.focus();
            previewFrame.contentWindow.print();
        } catch (e) {
            alert('Gagal membuka dialog print. Silakan download file dan print manual.');
        }
    }
</script>