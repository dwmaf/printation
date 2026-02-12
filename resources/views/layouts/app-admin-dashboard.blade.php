<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Koulen&display=swap"
        rel="stylesheet" />

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="flex p-6 gap-4 min-h-screen">
        <div class="bg-[#FAFAFA] rounded-xl flex flex-col w-[18%]">
            <div class="flex items-center mb-8 p-6">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-14 h-14">
                <h1 class="font-koulen text-4xl">Printation</h1>
            </div>
            <h2 class="font-bold text-[#8E8D8D] mb-2 ml-4">Menu</h2>
            <div class="flex flex-col">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex gap-3 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-500 bg-[#EFF6FF]' : 'text-[#B1B0AB]' }} cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.outlets') }}"
                    class="flex gap-3 {{ request()->routeIs('admin.outlets') ? 'text-indigo-500 bg-[#EFF6FF]' : 'text-[#B1B0AB]' }} cursor-pointer p-3 pl-6 w-full hover:bg-[#EFF6FF] hover:text-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" fill-rule="nonzero" d="M11 13v2H9v-2z"/><path fill="currentColor" d="M6.48 4a2 2 0 0 0-1.561.75l-3.05 3.813C1.083 9.545 1.783 11 3.04 11H4v7a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-7h2v8a1 1 0 1 0 2 0v-8h.96c1.258 0 1.957-1.455 1.171-2.437l-3.05-3.812A2 2 0 0 0 17.52 4zM8 11a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z"/></g></svg>
                    Outlets
                </a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="mt-auto text-red-600 w-full h-17 pr-4 pl-8 flex hover:bg-[#f4f4f4] transition-colors cursor-pointer rounded-b-xl hover:rounded-b-xl gap-3 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"
                    transform="rotate(-180)">
                    <path fill="#ff0000"
                        d="M11.25 19a.75.75 0 0 1 .75-.75h6a.25.25 0 0 0 .25-.25V6a.25.25 0 0 0-.25-.25h-6a.75.75 0 0 1 0-1.5h6c.966 0 1.75.784 1.75 1.75v12A1.75 1.75 0 0 1 18 19.75h-6a.75.75 0 0 1-.75-.75" />
                    <path fill="#ff0000"
                        d="M15.612 13.115a1 1 0 0 1-1 1H9.756q-.035.533-.086 1.066l-.03.305a.718.718 0 0 1-1.025.578a16.8 16.8 0 0 1-4.885-3.539l-.03-.031a.72.72 0 0 1 0-.998l.03-.031a16.8 16.8 0 0 1 4.885-3.539a.718.718 0 0 1 1.025.578l.03.305q.051.532.086 1.066h4.856a1 1 0 0 1 1 1z" />
                </svg>
                Logout
            </a>
        </div>

        <div class="flex flex-col flex-1">
            <div class="flex flex-col">
                {{-- HEADER --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-[#FAFAFA] p-6 rounded-xl">
                    <div>
                        <h1 class="text-4xl text-gray-900 font-koulen">@yield('title', 'Dashboard') </h1>
                        {{-- <h1 class="text-gray-500">Selamat datang, {{ Auth::user()->name }}</h1> --}}
                    </div>
                    <div class="flex items-center gap-3 min-w-0">
                        <div>
                            <img src="{{ asset('images/upa-pkk-logo.jpg') }}" alt="logo_upa_pkk" class="aspect-square w-13 rounded-full">
                        </div>
                        <div class="flex flex-col min-w-0 max-w-xs">
                            <p class="font-bold text-lg truncate">
                                UPA PKK UNTAN
                            </p>
                            <p class="text-md text-[#B1B0AB]">
                              Super admin
                                {{-- {{ $user->email }} --}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @yield('child')
        </div>
    </div>
</body>
</html>