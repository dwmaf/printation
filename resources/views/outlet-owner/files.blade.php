@extends('layouts.app')
@section('child')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">Manajemen File</h1>
                <p class="text-gray-500">Pantau dan bersihkan file yang menumpuk di semua Station.</p>
            </div>
            
            <form action="{{ route('outlet.files.clear-all') }}" method="POST" onsubmit="return confirm('Hapus SEMUA file di SEMUA station? Tindakan ini tidak bisa dibatalkan.')">
                @csrf @method('DELETE')
                <button class="bg-red-600 hover:bg-red-700 text-white font-black px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2 uppercase text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Bersihkan Semua Station
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-8">
            @foreach($stations as $station)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tight">STATION: {{ $station->name }}</h3>
                            <p class="text-xs text-gray-400">{{ $station->printFiles->count() }} file tersimpan</p>
                        </div>
                        
                        @if($station->printFiles->count() > 0)
                        <form action="{{ route('outlet.files.bulk-station', $station->id) }}" method="POST" onsubmit="return confirm('Hapus semua file di station ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-wider">
                                Kosongkan Station →
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <tbody class="divide-y divide-gray-100">
                                @forelse($station->printFiles as $file)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-700 truncate max-w-md">{{ $file->original_name }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase font-bold mt-1">
                                                {{ $file->pages }} Hal • {{ number_format($file->file_size / 1024, 1) }} KB • {{ $file->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('outlet.files.destroy', $file->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="bg-gray-100 hover:bg-red-100 text-gray-400 hover:text-red-600 p-2 rounded-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-12 text-center text-gray-300 italic text-sm">Tidak ada file aktif di station ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection