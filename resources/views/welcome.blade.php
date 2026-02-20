<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Printation') }} - UPA PKK Untan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Koulen&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Roboto', 'sans-serif'],
                            koulen: ['Koulen', 'cursive'],
                        },
                        colors: {
                            indigo: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                900: '#1e3a8a',
                            }
                        }
                    }
                }
            }
        </script>
    @endif
</head>

<body class="font-sans antialiased text-gray-700 bg-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto" alt="Printation Logo">
                    <div>
                        <span class="text-2xl font-koulen text-indigo-900 tracking-wide block leading-none">PRINTATION</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block leading-none">UPA PKK UNTAN</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Beranda</a>
                    <a href="#about" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Tentang Kami</a>
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Fitur</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/station') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5">
                                Dashboard
                            </a>
                        @else
                            <div class="flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-sm font-bold shadow-lg hover:bg-gray-800 transition-all transform hover:-translate-y-0.5">
                                        Daftar
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-500 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-white -z-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-indigo-50/50 rounded-bl-[100px] -z-10 hidden lg:block"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="space-y-8 animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        Solusi Cetak Cerdas
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-koulen text-gray-900 leading-tight">
                        Cetak Dokumen <br>
                        <span class="text-indigo-600">Lebih Mudah.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 max-w-lg leading-relaxed">
                        Layanan Self-Service Printing dari <strong>UPA PKK Universitas Tanjungpura</strong>. Upload dokumenmu, bayar secara digital, dan ambil cetakanmu di stasiun terdekat.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl bg-indigo-600 text-white text-base font-bold shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all transform hover:-translate-y-1 text-center">
                            Mulai Sekarang
                        </a>
                        <a href="#about" class="px-8 py-4 rounded-xl bg-white text-gray-700 border border-gray-200 text-base font-bold hover:bg-gray-50 transition-all text-center">
                            Pelajari Cara Kerja
                        </a>
                    </div>

                    <div class="pt-8 flex items-center gap-6 text-gray-400">
                        <div class="flex -space-x-4">
                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-500">U</div>
                            <div class="w-10 h-10 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-500">P</div>
                            <div class="w-10 h-10 rounded-full bg-gray-400 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-500">A</div>
                        </div>
                        <p class="text-sm">Didukung oleh <span class="font-bold text-gray-800">UPA PKK Untan</span></p>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative lg:h-[600px] w-full flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 to-indigo-600 rounded-[40px] rotate-3 opacity-10"></div>
                     <!-- Placeholder for app showcase or illustration -->
                    <img src="{{ asset('images/login-image.png') }}" class="relative z-10 w-full max-w-md hover:scale-105 transition-transform duration-500 drop-shadow-2xl" alt="App Preview">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-2">Tentang Kami</h2>
                <h3 class="text-3xl lg:text-4xl font-koulen text-gray-900">Inovasi UPA PKK UNTAN</h3>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center bg-gray-50 rounded-3xl p-8 md:p-12 border border-gray-100">
                <div class="flex justify-center">
                    <img src="{{ asset('images/upa-pkk-logo.jpg') }}" class="w-48 h-48 md:w-64 md:h-64 object-contain drop-shadow-lg rounded-full bg-white p-4" alt="UPA PKK Logo">
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Unit Penunjang Akademik Pengembangan Karier dan Kewirausahaan</h4>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Printation adalah salah satu unit usaha inovatif yang dikembangkan oleh UPA PKK Universitas Tanjungpura. Kami berkomitmen untuk menyediakan fasilitas penunjang yang modern, efisien, dan memudahkan civitas akademika dalam aktivitas sehari-hari.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-gray-700 font-medium">Layanan Digital Terintegrasi</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-gray-700 font-medium">Mendukung Kewirausahaan Kampus</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-gray-700 font-medium">Efisiensi Waktu & Biaya</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-900 text-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-600 rounded-full blur-[100px] opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-indigo-400 uppercase tracking-widest mb-2">Keunggulan</h2>
                <h3 class="text-3xl lg:text-4xl font-koulen text-white">Mengapa Printation?</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-800 p-8 rounded-2xl hover:bg-gray-750 transition-colors border border-gray-700 hover:border-indigo-500 group">
                    <div class="w-14 h-14 bg-indigo-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Self Service 24/7</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Akses layanan cetak kapan saja tanpa harus mengantri di percetakan konvensional. Cukup upload dan print.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-800 p-8 rounded-2xl hover:bg-gray-750 transition-colors border border-gray-700 hover:border-indigo-500 group">
                    <div class="w-14 h-14 bg-indigo-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Pembayaran QRIS</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Mendukung pembayaran non-tunai via QRIS. Cepat, aman, dan langsung terverifikasi otomatis.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-800 p-8 rounded-2xl hover:bg-gray-750 transition-colors border border-gray-700 hover:border-indigo-500 group">
                    <div class="w-14 h-14 bg-indigo-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Cloud Printing</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Simpan file di cloud kami dan cetak di stasiun manapun yang tersedia di area kampus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                         <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Logo">
                         <span class="text-xl font-koulen text-gray-900">PRINTATION</span>
                    </div>
                    <p class="text-gray-500 mb-6 max-w-sm">
                        Solusi cetak masa depan untuk lingkungan akademik yang lebih produktif dan efisien.
                    </p>
                    <div class="flex gap-4">
                        <!-- Social Icons Placeholder -->
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-indigo-600 hover:text-white transition-all">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-indigo-600 hover:text-white transition-all">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="font-bold text-gray-900 mb-4">Navigasi</h5>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#home" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
                        <li><a href="#about" class="hover:text-indigo-600 transition-colors">Tentang Kami</a></li>
                        <li><a href="#features" class="hover:text-indigo-600 transition-colors">Fitur</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition-colors">Login / Register</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-gray-900 mb-4">Kontak</h5>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>Pontianak, Kalimantan Barat</li>
                        <li>Universitas Tanjungpura</li>
                        <li>upapkk@untan.ac.id</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Printation by UPA PKK Untan. All rights reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-gray-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-600 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
