@extends('layouts.app-admin-dashboard')
@section('child')

<div>
    
    {{-- Header --}}
    {{-- <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Super Admin Dashboard</h1>
            <p class="text-gray-500">Kelola Data Mitra Outlet & Akses</p>
        </div>

        <div class="flex items-center gap-6">
            <a href="{{ route('admin.transactions') }}" class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 font-bold text-gray-700">
                Transactions
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-red-600 font-bold hover:underline">Logout</button>
            </form>
        </div>
    </div> --}}

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex gap-4">
        {{-- TABEL DAFTAR OUTLET --}}
        <div class="w-[75%]">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden h-full flex flex-col justify-between">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-gray-600">Nama Outlet</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Kuota Station</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Status</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($outlets as $outlet)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $outlet->name }}</div>
                                <div class="text-xs text-gray-500">{{ $outlet->address }}</div>
                            </td>
                            <td class="p-4">
                                @if($outlet->owner)
                                    <div class="text-sm font-medium">{{ $outlet->owner->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $outlet->owner->email }}</div>
                                @else
                                    <span class="text-red-500 text-xs font-medium">Belum ada akun</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">
                                    Max: {{ $outlet->max_stations }} PC
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="text-green-600 text-xs font-bold uppercase">Aktif</span>
                            </td>
                            <td class="text-center">
                                <button onclick="openEditModal({{ $outlet->id }}, '{{ $outlet->name }}', {{ $outlet->max_stations }}, '{{ $outlet->status ?? 'active' }}')" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23px" height="23px" viewBox="0 0 24 24"><g fill="#efb100" fill-rule="evenodd" clip-rule="evenodd"><path d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352z"/><path d="M19.846 4.318a2.2 2.2 0 0 0-.437-.692a2 2 0 0 0-.654-.463a1.92 1.92 0 0 0-1.544 0a2 2 0 0 0-.654.463l-.546.578l2.852 3.02l.546-.579a2.1 2.1 0 0 0 .437-.692a2.24 2.24 0 0 0 0-1.635M17.45 8.721L14.597 5.7L9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.5.5 0 0 0 .255-.145l4.778-5.06Z"/></g></svg>
                                </button>
                                {{-- Modal Edit Outlet --}}
                                <div id="editModal" class="hidden inset-0 fixed flex items-center justify-center z-50">
                                    <div class="bg-white rounded-xl shadow-lg p-8 w-125">
                                        <div class="flex justify-between items-center mb-6">
                                            <h3 class="text-2xl font-bold text-gray-800">Edit Outlet</h3>
                                        </div>

                                        <form id="editForm" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div>
                                                <label class="block text-sm font-semibold mb-2 text-left">Nama Outlet</label>
                                                <input type="text" id="modal_outlet_name" class="w-full border-2 border-gray-300 rounded-md py-2 px-4 focus:outline-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold mb-2 text-left">Kuota Station</label>
                                                <input type="number" name="max_stations" id="modal_max_stations" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-none focus:border-indigo-500" min="1" required>
                                                <p class="text-gray-500 text-left text-xs mt-1">Jumlah maksimal laptop yang dapat login</p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold mb-2 text-left">Status</label>
                                                <select name="status" id="modal_status" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-none focus:border-indigo-500 cursor-pointer">
                                                    <option value="active">Aktif</option>
                                                    <option value="inactive">Nonaktif</option>
                                                </select>
                                            </div>

                                            <div class="flex gap-3 mt-6">
                                                <button type="button" onclick="closeEditModal()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition cursor-pointer">
                                                    Batal
                                                </button>
                                                <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition cursor-pointer">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($outlets->isEmpty())
                <div class="p-8 text-center text-gray-500 flex flex-col items-center justify-center h-full gap-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80px" height="80px" viewBox="0 0 14 14"><path fill="none" stroke="#6a7282" stroke-linecap="round" stroke-linejoin="round" d="M7.343 2H1a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h12a.5.5 0 0 0 .5-.5V7M6 11l-1 2.5M8 11l1 2.5m-5 0h6m3.5-8.922l-4-4m4 0l-4 4" stroke-width="1"/></svg>
                    <p class="text-center font-medium">Belum ada outlet yang terdaftar</p>
                </div>
                @endif
                <div>
                    <hr class="border-gray-500 mb-4">
                    <div class="flex justify-between px-6 pb-4">
                        <p class="text-gray-400 text-sm">Menampilkan 1 hingga 10 dari 24 daftar</p>
                        <div class="flex items-center gap-1">
                            <button class="cursor-pointer bg-gray-300 w-7 flex justify-center rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.5px" class="-rotate-180" height="23px" viewBox="0 0 12 24"><path fill="#000" fill-rule="evenodd" d="M10.157 12.711L4.5 18.368l-1.414-1.414l4.95-4.95l-4.95-4.95L4.5 5.64l5.657 5.657a1 1 0 0 1 0 1.414"/></svg>
                            </button>
                            <input type="number" placeholder="1" class="w-10 border border-gray-400 text-black [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:outline-none text-center font-medium rounded-md">
                            <button class="cursor-pointer bg-gray-300 w-7 flex justify-center rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.5px" height="23px" viewBox="0 0 12 24"><path fill="#000" fill-rule="evenodd" d="M10.157 12.711L4.5 18.368l-1.414-1.414l4.95-4.95l-4.95-4.95L4.5 5.64l5.657 5.657a1 1 0 0 1 0 1.414"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- FORM TAMBAH OUTLET --}}
        <div class="w-[25%]">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-koulen text-3xl">Registrasi Mitra Baru</h3>
                <form action="{{ route('admin.outlets.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold">Nama Outlet / Toko</label>
                        <input type="text" name="outlet_name" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-none text-sm" required placeholder="Berkah Print">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Alamat</label>
                        <input type="text" name="address" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-none text-sm" placeholder="Jl. Mawar No. 10">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Jatah Station (Laptop)</label>
                        <input type="number" name="max_stations" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-none text-sm" required value="1" min="1">
                        <p class="text-gray-400 text-sm">Berapa laptop yang boleh login.</p>
                    </div>

                    <div>
                        <p class="font-koulen text-3xl">Data Akun Owner</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold">Nama Pemilik</label>
                                <input type="text" name="owner_name" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-indigo-500 text-sm" required placeholder="Nama Owner">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold">Email Login</label>
                                <input type="email" name="owner_email" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-indigo-500 text-sm" required placeholder="email@owner.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold">Password Login</label>
                                <div class="relative">
                                    <input type="password" name="owner_password" class="w-full border-2 border-gray-400 rounded-md py-2 px-4 focus:outline-indigo-500 text-sm" required placeholder="Buat password untuk owner">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition cursor-pointer">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, name, maxStations, status) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('modal_outlet_name').value = name;
        document.getElementById('modal_max_stations').value = maxStations;
        document.getElementById('modal_status').value = status;
        document.getElementById('editForm').action = `/admin/outlets/${id}`;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection
