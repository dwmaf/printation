@extends('layouts.app')

@section('child')
<div class="min-h-screen bg-gray-100 p-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Admin - Monitoring Transaksi</h1>
            {{-- <p class="text-gray-500">Konfirmasi pembayaran sebelum user bisa print</p> --}}
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('admin.outlets') }}"
               class="bg-white border px-4 py-2 rounded-lg font-bold text-gray-700 hover:bg-gray-50">
                Outlet
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-red-600 font-bold hover:underline">Logout</button>
            </form>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">Order ID</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">File</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Amount</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Status</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Created</th>
                    {{-- header action akan dicomment --}}
                    <th class="p-4 text-sm font-semibold text-gray-600 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4">
                            <div class="font-bold text-gray-800">{{ $tx->order_id }}</div>
                            <div class="text-xs text-gray-400">ID: {{ $tx->id }}</div>
                        </td>

                        <td class="p-4">
                            @if($tx->printfile)
                                <div class="font-semibold text-gray-800">
                                    {{ $tx->printfile->original_name ?? $tx->printfile->filename }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Printfile ID: {{ $tx->printfile->id }}
                                </div>
                            @else
                                <span class="text-red-500 text-xs font-bold">Printfile missing</span>
                            @endif
                        </td>

                        <td class="p-4">
                            <div class="font-bold text-gray-800">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </div>
                        </td>

                        <td class="p-4">
                            @php
                                $badge = match($tx->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                {{ strtoupper($tx->status) }}
                            </span>

                            @if($tx->paid_at)
                                <div class="text-[11px] text-gray-400 mt-1">
                                    Paid at: {{ $tx->paid_at }}
                                </div>
                            @endif
                        </td>

                        <td class="p-4 text-sm text-gray-600">
                            {{ $tx->created_at?->diffForHumans() }}
                        </td>

                        {{-- td berikut akan dicomment --}}
                        <td class="p-4 text-right">
                            @if($tx->status === 'pending')
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.transactions.approve', $tx->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-green-600 hover:bg-green-500 text-white font-bold px-4 py-2 rounded-lg">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.transactions.reject', $tx->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-red-600 hover:bg-red-500 text-white font-bold px-4 py-2 rounded-lg">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 font-bold">No action</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-8 text-center text-gray-500" colspan="6">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
