@extends('layouts.app')

@section('child')
    <div class="flex p-6 gap-4 min-h-screen">
        <div class="bg-[#FAFAFA] rounded-xl flex flex-col w-[18%]">
            <div class="flex items-center mb-8 p-6">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-14 h-14">
                <h1 class="font-koulen text-4xl">Printation</h1>
            </div>
            <h2 class="font-bold text-[#8E8D8D] mb-2 ml-4">Menu</h2>
            <div class="flex flex-col">
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24"><path fill="currentColor" d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z"/></svg>Dashboard</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12a9.97 9.97 0 0 0 1.3 4.935l-1.249 3.749a1 1 0 0 0 1.265 1.265l3.749-1.25A9.96 9.96 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2m0 6c-.902 0-1.731.297-2.4.8a1 1 0 1 1-1.2-1.6a6 6 0 0 1 8.4 8.4a1 1 0 0 1-1.598-1.2A4 4 0 0 0 12 8m-5 3a1 1 0 0 1 1 1a4 4 0 0 0 4 4a1 1 0 1 1 0 2a6 6 0 0 1-6-6a1 1 0 0 1 1-1m5-1a2 2 0 1 0 0 4a2 2 0 0 0 0-4" clip-rule="evenodd"/></svg>Payments</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 512 512"><path fill="currentColor" d="M16 352a48.05 48.05 0 0 0 48 48h133.88l-4 32H144a16 16 0 0 0 0 32h224a16 16 0 0 0 0-32h-49.88l-4-32H448a48.05 48.05 0 0 0 48-48v-48H16Zm240-16a16 16 0 1 1-16 16a16 16 0 0 1 16-16M496 96a48.05 48.05 0 0 0-48-48H64a48.05 48.05 0 0 0-48 48v192h480Z"/></svg>Stations</ul>
                
                <ul class="flex gap-3 text-[#B1B0AB] cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><g fill="currentColor"><path d="m12 2l.117.007a1 1 0 0 1 .876.876L13 3v4l.005.15a2 2 0 0 0 1.838 1.844L15 9h4l.117.007a1 1 0 0 1 .876.876L20 10v9a3 3 0 0 1-2.824 2.995L17 22H7a3 3 0 0 1-2.995-2.824L4 19V5a3 3 0 0 1 2.824-2.995L7 2z"/><path d="M19 7h-4l-.001-4.001z"/></g></svg>Files</ul>
                
            </div>

            <div class="mt-auto text-red-600 w-full h-17 pr-4 pl-8 flex hover:bg-[#f4f4f4] transition-colors cursor-pointer rounded-b-xl hover:rounded-b-xl gap-3 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24" transform="rotate(-180)"><path fill="#ff0000" d="M11.25 19a.75.75 0 0 1 .75-.75h6a.25.25 0 0 0 .25-.25V6a.25.25 0 0 0-.25-.25h-6a.75.75 0 0 1 0-1.5h6c.966 0 1.75.784 1.75 1.75v12A1.75 1.75 0 0 1 18 19.75h-6a.75.75 0 0 1-.75-.75"/><path fill="#ff0000" d="M15.612 13.115a1 1 0 0 1-1 1H9.756q-.035.533-.086 1.066l-.03.305a.718.718 0 0 1-1.025.578a16.8 16.8 0 0 1-4.885-3.539l-.03-.031a.72.72 0 0 1 0-.998l.03-.031a16.8 16.8 0 0 1 4.885-3.539a.718.718 0 0 1 1.025.578l.03.305q.051.532.086 1.066h4.856a1 1 0 0 1 1 1z"/></svg>
                Logout
            </div>
        </div>

        <div class="flex flex-col flex-1">
            <div class="flex flex-col">
                {{-- HEADER --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-[#FAFAFA] p-6 rounded-xl">
                    <div>
                        <h1 class="text-4xl text-gray-900 font-koulen">Dashboard </h1>
                        {{-- <h1 class="text-gray-500">Selamat datang, {{ Auth::user()->name }}</h1> --}}
                    </div>
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-3 bg-gray-100 rounded-full shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24"><path fill="#6155F5" fill-rule="evenodd" d="M8 7a4 4 0 1 1 8 0a4 4 0 0 1-8 0m0 6a5 5 0 0 0-5 5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3a5 5 0 0 0-5-5z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="flex flex-col min-w-0 max-w-xs">
                            <p class="font-bold text-lg truncate">
                                {{ $outlet->name }}
                            </p>
                            <p class="text-md text-[#B1B0AB]">
                                {{ $user->email }}
                            </p>
                        </div>
                        {{-- <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                            Paket: {{ $outlet->max_stations }} Station
                        </span> --}}
                    </div>
                </div>
        
        
                {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
                    {{-- KOLOM KIRI untuk statisktik 
                    <div class="lg:col-span-2 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Card Pendapatan -->
                            <div class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-green-500">
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            </div>
        
                            <!-- Card Antrian -->
                            <a href="{{ route('outlet.payments') }}" class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-yellow-500 hover:bg-yellow-50 transition block">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Antrian Bayar</p>
                                        <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $pendingCount }}</h3>
                                    </div>
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded">VERIFIKASI SEKARANG →</span>
                                </div>
                            </a>
                        </div>
        
                        {{-- Placeholder untuk Chart atau Data Lain di masa depan 
                        <div class="bg-white p-12 rounded-2xl shadow-lg border border-gray-100 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <h3 class="font-bold text-gray-800">Modul Statistik Segera Hadir</h3>
                            <p class="text-gray-400 text-sm">Pantau performa harian outlet Anda di sini.</p>
                        </div>
                    </div>
    
                    {{-- KOLOM KANAN: PENGATURAN OUTLET / QRIS (NEW) 
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                            <h2 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                QRIS Pembayaran
                            </h2>
                            
                            <div class="flex flex-col items-center p-4 bg-gray-50 rounded-xl mb-4 border border-gray-100">
                                @if($outlet->qris_path)
                                    <img src="{{ asset('storage/' . $outlet->qris_path) }}" class="h-32 w-auto object-contain rounded shadow-sm mb-3">
                                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">QRIS AKTIF</span>
                                @else
                                    <div class="h-32 w-32 flex items-center justify-center text-gray-300 border-2 border-dashed border-gray-200 rounded-lg mb-3">
                                        No QRIS
                                    </div>
                                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">BELUM DISET</span>
                                @endif
                            </div>
        
                            <a href="{{ route('outlet.editQRIS') }}" class="block w-full text-center bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl transition shadow-lg">
                                GANTI QRIS
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="grid grid-cols-6 grid-rows-9 gap-4 bg-[#FAFAFA] p-6 rounded-xl flex-1">
                <div class="col-span-2 row-span-3 bg-white shadow-md rounded-xl p-6">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="font-bold text-2xl">Pemasukan</h3>
                            <p class="font-medium text-sm text-[#C2C2C2]">10 Feb 2026</p>
                        </div>
                        <div class="p-2 bg-[#60FC99] rounded-3xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24"><path fill="#0D7432" fill-rule="evenodd" d="M20.41 9.86a3 3 0 0 0-.175-.003H17.8c-1.992 0-3.698 1.581-3.698 3.643s1.706 3.643 3.699 3.643h2.433q.092.001.175-.004a1.7 1.7 0 0 0 1.586-1.581c.004-.059.004-.122.004-.18v-3.756c0-.058 0-.121-.004-.18a1.7 1.7 0 0 0-1.585-1.581m-2.823 4.611c.513 0 .93-.434.93-.971s-.417-.971-.93-.971s-.929.434-.929.971s.416.971.93.971" clip-rule="evenodd"/><path fill="#0D7432" fill-rule="evenodd" d="M20.234 18.6a.214.214 0 0 1 .214.27c-.194.692-.501 1.282-.994 1.778c-.721.727-1.636 1.05-2.766 1.203c-1.098.149-2.5.149-4.272.149h-2.037c-1.771 0-3.174 0-4.272-.149c-1.13-.153-2.045-.476-2.766-1.203C2.62 19.923 2.3 19 2.148 17.862C2 16.754 2 15.34 2 13.555v-.11c0-1.785 0-3.2.148-4.306C2.3 8 2.62 7.08 3.34 6.351c.721-.726 1.636-1.05 2.766-1.202C7.205 5 8.608 5 10.379 5h2.037c1.771 0 3.174 0 4.272.149c1.13.153 2.045.476 2.766 1.202c.493.497.8 1.087.994 1.78a.214.214 0 0 1-.214.269h-2.433c-2.734 0-5.143 2.177-5.143 5.1s2.41 5.1 5.144 5.1zM5.614 8.886a.725.725 0 0 0-.722.728c0 .403.323.729.722.729H9.47c.4 0 .723-.326.723-.729a.726.726 0 0 0-.723-.728z" clip-rule="evenodd"/><path fill="#0D7432" d="m7.777 4.024l1.958-1.443a2.97 2.97 0 0 1 3.53 0l1.969 1.451C14.41 4 13.49 4 12.483 4h-2.17c-.922 0-1.769 0-2.536.024"/></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-bold mb-1">Rp 10.000.000</p>
                    <div class="flex gap-2">
                        <img src=" {{ asset('images/trend-up.svg') }} " alt="">
                        <span class="text-[#05E7C6] font-bold">12%</span> dari kemarin
                    </div>
                </div>
                <div class="col-span-2 row-span-3 col-start-3 bg-white shadow-md rounded-xl p-6">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="font-bold text-2xl">Lembar Dicetak</h3>
                            <p class="font-medium text-sm text-[#C2C2C2]">10 Feb 2026</p>
                        </div>
                        <div class="p-2 bg-[#FEC53D] rounded-3xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24"><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="#9F7208" d="M16 16a1 1 0 0 1 .993.883L17 17v4a1 1 0 0 1-.883.993L16 22H8a1 1 0 0 1-.993-.883L7 21v-4a1 1 0 0 1 .883-.993L8 16zm3-9a3 3 0 0 1 3 3v7a2 2 0 0 1-2 2h-1v-3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3H4a2 2 0 0 1-2-2v-7a3 3 0 0 1 3-3zm-2 2h-2a1 1 0 0 0-.117 1.993L15 11h2a1 1 0 0 0 .117-1.993zm0-7a1 1 0 0 1 1 1v2H6V3a1 1 0 0 1 1-1z"/></g></svg>
                        </div>
                    </div>
                    <p class="text-4xl font-bold mb-1">10.000.000</p>
                    <div class="flex gap-2">
                        <img src=" {{ asset('images/trend-down.svg') }} " alt="">
                        <span class="text-[#F93C65] font-bold">12%</span> dari kemarin
                    </div>
                </div>
                <div class="col-span-2 row-span-3 col-start-5 bg-white shadow-md rounded-xl p-6 flex justify-between">
                    <div>
                        <p class="text-3xl font-bold">Rp 77.000.000</p>
                        <p class="mb-1 font-medium text-[#C2C2C2]">Total pemasukan</p>
                        <p class="text-3xl font-bold">77.000.000</p>
                        <p class="font-medium text-[#C2C2C2]">Total lembar telah dicetak</p>
                    </div>
                    <div class="p-2 bg-[#96befe] rounded-3xl h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24"><g fill="none" stroke="#174fa8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5.697M18 14v4h4m-4-7V7a2 2 0 0 0-2-2h-2"/><path d="M8 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m6 13a4 4 0 1 0 8 0a4 4 0 1 0-8 0m-6-7h4m-4 4h3"/></g></svg>
                    </div>
                </div>
                <div id="chartDashboard" class="col-span-3 row-span-6 row-start-4 bg-white shadow-md rounded-xl">
                </div>
                {{-- <div class="col-span-3 row-span-3 col-start-1 row-start-7 bg-white shadow-md rounded-xl p-6">8</div> --}}
                <div class="col-span-2 row-span-4 col-start-4 row-start-6 bg-white shadow-md rounded-xl p-6">
                    <div class="flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m15 16l-2.414-2.414A2 2 0 0 1 12 12.172V6"/></g></svg>
                        <p class="font-medium">File Terbaru</p>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <div class="flex justify-between items-center mt-2 p-2  rounded-lg">
                            <p class="font-bold text-red-600">PDF</p>
                            <div class="flex flex-col">
                                <p>RahasiaNegara.pdf</p>
                                <p class="text-xs">2 jam lalu</p>
                            </div>
                            <span class="p-1 bg-green-200 rounded-md text-green-800 text-sm">Rp 3.567</span>
                        </div>
                        <div class="flex justify-between items-center mt-2 p-2  rounded-lg">
                            <p class="font-bold text-red-600">PDF</p>
                            <div class="flex flex-col">
                                <p>RahasiaNegara.pdf</p>
                                <p class="text-xs">2 jam lalu</p>
                            </div>
                            <span class="p-1 bg-green-200 rounded-md text-green-800 text-sm">Rp 3.567</span>
                        </div>
                    </div>
                </div>
                <a href={{ route('outlet.payments') }}" class="col-span-2 row-span-2 col-start-4 row-start-4 bg-white shadow-md rounded-xl p-6 hover:bg-yellow-50 transition flex justify-between items-end">
                    <div>
                        <p class="text-3xl font-black text-gray-800 mt-1">{{ $pendingCount }}</p>
                        <p class="text-sm font-medium text-gray-400">Antrian Bayar</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded h-fit flex items-center gap-1">VERIFIKASI SEKARANG <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(90)"" width="15px" height="15px" viewBox="0 0 24 24"><path fill="none" stroke="#9F7208" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m6-8l-6-6m-6 6l6-6"/></svg></span>
                </a>
                <div class="row-span-6 col-start-6 row-start-4 bg-white shadow-md rounded-xl p-6 flex flex-col items-center">
                    @if($outlet->qris_path)
                        <img src="{{ asset('storage/' . $outlet->qris_path) }}" class="h-32 w-auto object-contain rounded shadow-sm mb-3">
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">QRIS AKTIF</span>
                    @else
                        <div class="h-32 w-32 flex items-center justify-center text-gray-300 border-2 border-dashed border-gray-200 rounded-lg mb-3">
                            No QRIS
                        </div>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">BELUM DISET</span>
                    @endif

                    <p class="text-xs mt-4">Upload QRIS toko Anda agar pelanggan bisa membayar secara mandiri.</p>

                    <a href="{{ route('outlet.editQRIS') }}" class="block w-full text-center bg-indigo-600 font-bold py-2 rounded-lg hover:bg-indigo-500 text-white mt-4">
                    GANTI QRIS
                    </a>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            var tanggal = ["1 Feb", "2 Feb", "3 Feb", "4 Feb", "5 Feb", "6 Feb", "7 Feb"];
            var pemasukan = [20000, 15000, 30000, 20000, 15000, 30000, 20000];
            var lembar = [10, 7, 15, 10, 7, 15, 10];

            var options = {
            series: [
                {
                name: "Pemasukan",
                type: "column",
                data: pemasukan
                },
                {
                name: "Lembar Dicetak",
                type: "column",
                data: lembar
                }
            ],
            title: {
                text: 'Statistik Mingguan',
                align: 'left',
                offsetY: 15,
                offsetX: 12,
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            chart: {
                height: 350,
                type: "line",
                toolbar: {
                    show: false
                }
            },
            // grid: {
            //     padding: {
            //         right: 30,
            //     }
            // },
            stroke: {
                width: [2, 2],
                curve: "smooth"
            },
            // fill: {
            //     type: ['gradient', 'solid'],
            //     gradient: {
            //         shade: 'light',
            //         type: "vertical",
            //         opacityFrom: 0.35,
            //         opacityTo: 0.0,
            //         stops: [0, 100]
            //     }
            // },
            plotOptions: {
                // bar: {
                //     borderRadius: 6,
                //     columnWidth: '40%'
                // }
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 6
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: tanggal
            },
            yaxis: [
                {
                    title: {
                        text: "Rupiah"
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: "Lembar"
                    }
                }
                ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val, { seriesIndex }) {
                    if (seriesIndex === 0) {
                        return "Rp " + val.toLocaleString();
                    }
                    return val + " lembar";
                    }
                    }
                },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -20,
            },
            };

            var chart = new ApexCharts(
                document.querySelector("#chartDashboard"), options
            );

            chart.render();
        </script>
    </div>
@endsection
