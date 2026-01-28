@php //Cuma data dummy untuk test tampilan
    // Skenario 1: QR Code
    // $files = []; File kosong

    // Skenario 2: List File
    $files = [
        ['filename' => 'Laporan_Tahunan.pdf', 'type' => 'PDF'],
    ];

    // QR Code sementara
    $qrCode = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=Demo" class="w-full h-full">';
@endphp

<div class="min-h-screen bg-gray-900 text-white font-sans flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2"></div>

    <div class="mb-10 text-center z-10">
        <h1 class="text-4xl font-black text-white tracking-widest uppercase drop-shadow-lg">
            Print Station
        </h1>
    </div>

    @if(empty($files))
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

            <div class="grid grid-cols-1 gap-4">
                @foreach($files as $file)
                <div class="bg-gray-800 hover:bg-gray-600 border border-gray-700 p-5 rounded-xl flex justify-between items-center shadow-lg">
                    
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-xl font-bold shadow-inner
                            {{ $file['type'] == 'PDF' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }}">
                            {{ $file['type'] }}
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-white mb-1">{{ $file['filename'] }}</h3>
                        </div>
                    </div>

                    <button class="bg-white text-gray-900 hover:bg-blue-500 hover:text-white px-8 py-3 rounded-lg font-bold shadow-lg transition-all flex items-center group">
                        <span class="mr-2">PRINT</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 21C7.45 21 6.97933 20.8043 6.588 20.413C6.19667 20.0217 6.00067 19.5507 6 19V17H4C3.45 17 2.97933 16.8043 2.588 16.413C2.19667 16.0217 2.00067 15.5507 2 15V11C2 10.15 2.29167 9.43767 2.875 8.863C3.45833 8.28833 4.16667 8.00067 5 8H19C19.85 8 20.5627 8.28767 21.138 8.863C21.7133 9.43833 22.0007 10.1507 22 11V15C22 15.55 21.8043 16.021 21.413 16.413C21.0217 16.805 20.5507 17.0007 20 17H18V19C18 19.55 17.8043 20.021 17.413 20.413C17.0217 20.805 16.5507 21.0007 16 21H8ZM4 15H6C6 14.45 6.196 13.9793 6.588 13.588C6.98 13.1967 7.45067 13.0007 8 13H16C16.55 13 17.021 13.196 17.413 13.588C17.805 13.98 18.0007 14.4507 18 15H20V11C20 10.7167 19.904 10.4793 19.712 10.288C19.52 10.0967 19.2827 10.0007 19 10H5C4.71667 10 4.47933 10.096 4.288 10.288C4.09667 10.48 4.00067 10.7173 4 11V15ZM16 8V5H8V8H6V5C6 4.45 6.196 3.97933 6.588 3.588C6.98 3.19667 7.45067 3.00067 8 3H16C16.55 3 17.021 3.196 17.413 3.588C17.805 3.98 18.0007 4.45067 18 5V8H16ZM18 12.5C18.2833 12.5 18.521 12.404 18.713 12.212C18.905 12.02 19.0007 11.7827 19 11.5C18.9993 11.2173 18.9033 10.98 18.712 10.788C18.5207 10.596 18.2833 10.5 18 10.5C17.7167 10.5 17.4793 10.596 17.288 10.788C17.0967 10.98 17.0007 11.2173 17 11.5C16.9993 11.7827 17.0953 12.0203 17.288 12.213C17.4807 12.4057 17.718 12.5013 18 12.5ZM16 19V15H8V19H16Z" fill="black"/>
                        </svg>
                    </button>

                </div>
                @endforeach
            </div>

        </div>

    @endif
</div>