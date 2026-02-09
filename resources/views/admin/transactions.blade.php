@extends('layouts.app')

@section('child')
<div class="p-8">
    <h1 class="text-3xl font-black mb-6">Admin - Konfirmasi Pembayaran</h1>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-800 rounded-xl mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-3">Order</th>
                    <th class="py-3">File ID</th>
                    <th class="py-3">Nominal</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Print Config</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr class="border-b">
                        <td class="py-3 font-bold">{{ $tx->order_id }}</td>
                        <td class="py-3">{{ $tx->file_id }}</td>
                        <td class="py-3 font-bold">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                        <td class="py-3">
                            @if($tx->status === 'pending')
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-bold">PENDING</span>
                            @elseif($tx->status === 'paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-bold">PAID</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-bold">REJECTED</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <pre class="text-xs whitespace-pre-wrap">{{ $tx->print_config }}</pre>
                        </td>
                        <td class="py-3">
                            @if($tx->status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.transactions.confirm', $tx) }}">
                                        @csrf
                                        <button class="px-4 py-2 rounded-lg bg-green-600 text-white font-bold">CONFIRM</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.transactions.reject', $tx) }}">
                                        @csrf
                                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-bold">REJECT</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-400">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
