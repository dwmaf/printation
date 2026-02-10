@extends('layouts.app')
@section('child')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('outlet.dashboard') }}" class="text-blue-600 font-bold flex items-center gap-2 mb-6 hover:underline">
            ← Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-black text-gray-900">Pengaturan QRIS</h1>
                <p class="text-gray-500">Upload QRIS toko Anda agar pelanggan bisa membayar secara mandiri.</p>
            </div>

            <div class="p-8">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- QRIS Saat Ini --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-3">QRIS Aktif Saat Ini</label>
                        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center min-h-[300px]">
                            @if($outlet->qris_path)
                                <img src="{{ asset('storage/' . $outlet->qris_path) }}" class="max-w-full h-auto rounded-lg shadow-md" alt="QRIS Aktif">
                            @else
                                <div class="text-center text-gray-300">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-xs font-bold uppercase">Belum Ada QRIS</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form Upload --}}
                    <div>
                        <form action="{{ route('outlet.updateQRIS') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload File Baru</label>
                                <div class="relative border-2 border-gray-300 border-dashed rounded-xl p-6 hover:border-blue-400 transition-colors group">
                                    <input type="file" name="qris_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="text-center">
                                        <svg class="w-10 h-10 mx-auto text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">Klik atau seret gambar ke sini</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG atau JPEG (Maks. 2MB)</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl transition shadow-lg shadow-blue-600/20 uppercase tracking-wider">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection