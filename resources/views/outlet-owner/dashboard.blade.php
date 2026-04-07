@extends('layouts.app-dashboard')
@section('title', 'Dashboard')
@section('child')
    

    <div class="grid grid-cols-6 grid-rows-9 gap-4 bg-[#FAFAFA] p-6 rounded-xl flex-1">
        <div class="col-span-2 row-span-3 bg-white shadow-md rounded-xl p-6">
            <div class="flex justify-between">
                <div>
                    <h3 class="font-bold text-2xl">Pemasukan</h3>
                    <p class="font-medium text-sm text-[#C2C2C2]">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="p-2 bg-[#60FC99] rounded-3xl">
                    {{-- if ($percentageChange >= 0) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24">
                            <path fill="#0D7432" fill-rule="evenodd"
                                d="M20.41 9.86a3 3 0 0 0-.175-.003H17.8c-1.992 0-3.698 1.581-3.698 3.643s1.706 3.643 3.699 3.643h2.433q.092.001.175-.004a1.7 1.7 0 0 0 1.586-1.581c.004-.059.004-.122.004-.18v-3.756c0-.058 0-.121-.004-.18a1.7 1.7 0 0 0-1.585-1.581m-2.823 4.611c.513 0 .93-.434.93-.971s-.417-.971-.93-.971s-.929.434-.929.971s.416.971.93.971"
                                clip-rule="evenodd" />
                            <path fill="#0D7432" fill-rule="evenodd"
                                d="M20.234 18.6a.214.214 0 0 1 .214.27c-.194.692-.501 1.282-.994 1.778c-.721.727-1.636 1.05-2.766 1.203c-1.098.149-2.5.149-4.272.149h-2.037c-1.771 0-3.174 0-4.272-.149c-1.13-.153-2.045-.476-2.766-1.203C2.62 19.923 2.3 19 2.148 17.862C2 16.754 2 15.34 2 13.555v-.11c0-1.785 0-3.2.148-4.306C2.3 8 2.62 7.08 3.34 6.351c.721-.726 1.636-1.05 2.766-1.202C7.205 5 8.608 5 10.379 5h2.037c1.771 0 3.174 0 4.272.149c1.13.153 2.045.476 2.766 1.202c.493.497.8 1.087.994 1.78a.214.214 0 0 1-.214.269h-2.433c-2.734 0-5.143 2.177-5.143 5.1s2.41 5.1 5.144 5.1zM5.614 8.886a.725.725 0 0 0-.722.728c0 .403.323.729.722.729H9.47c.4 0 .723-.326.723-.729a.726.726 0 0 0-.723-.728z"
                                clip-rule="evenodd" />
                            <path fill="#0D7432"
                                d="m7.777 4.024l1.958-1.443a2.97 2.97 0 0 1 3.53 0l1.969 1.451C14.41 4 13.49 4 12.483 4h-2.17c-.922 0-1.769 0-2.536.024" />
                        </svg>
                    {{-- @else --}}
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                            <path fill="#D32F2F"
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m5 11h-4v4h-2v-4H7v-2h4V7h2v4h4z" />
                        </svg> --}}
                    {{-- @endif --}}
                </div>
            </div>
            <p class="text-4xl font-bold mb-1">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
            <div class="flex gap-2">
                @if ($percentageChange >= 0)
                    <img src=" {{ asset('images/trend-up.svg') }} " alt="">
                    <span class="text-[#05E7C6] font-bold">{{ number_format($percentageChange, 0) }}%</span> dari
                    kemarin
                @else
                    <img src="{{ asset('images/trend-down.svg') }}" alt="">
                    <span class="text-[#F93C65] font-bold">{{ number_format(abs($percentageChange), 0) }}%</span>
                    dari kemarin
                @endif
            </div>
        </div>
        <div class="col-span-2 row-span-3 col-start-3 bg-white shadow-md rounded-xl p-6">
            <div class="flex justify-between">
                <div>
                    <h3 class="font-bold text-2xl">Lembar Dicetak</h3>
                    <p class="font-medium text-sm text-[#C2C2C2]">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="p-2 bg-[#FEC53D] rounded-3xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24">
                        <g fill="none">
                            <path
                                d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                            <path fill="#9F7208"
                                d="M16 16a1 1 0 0 1 .993.883L17 17v4a1 1 0 0 1-.883.993L16 22H8a1 1 0 0 1-.993-.883L7 21v-4a1 1 0 0 1 .883-.993L8 16zm3-9a3 3 0 0 1 3 3v7a2 2 0 0 1-2 2h-1v-3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3H4a2 2 0 0 1-2-2v-7a3 3 0 0 1 3-3zm-2 2h-2a1 1 0 0 0-.117 1.993L15 11h2a1 1 0 0 0 .117-1.993zm0-7a1 1 0 0 1 1 1v2H6V3a1 1 0 0 1 1-1z" />
                        </g>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold mb-1">{{ number_format($pagesToday, 0, ',', '.') }}</p>
            <div class="flex gap-2">
                @if ($pagesPercentageChange >= 0)
                    <img src=" {{ asset('images/trend-up.svg') }} " alt="">
                    <span class="text-[#05E7C6] font-bold">{{ number_format($pagesPercentageChange, 0) }}%</span>
                    dari kemarin
                @else
                    <img src="{{ asset('images/trend-down.svg') }}" alt="">
                    <span class="text-[#F93C65] font-bold">{{ number_format(abs($pagesPercentageChange), 0) }}%</span>
                    dari kemarin
                @endif
            </div>
        </div>
        <div class="col-span-2 row-span-3 col-start-5 bg-white shadow-md rounded-xl p-6 flex justify-between">
            <div>
                <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="mb-1 font-medium text-[#C2C2C2]">Total pemasukan</p>
                <p class="text-3xl font-bold">{{ number_format($totalPages, 0, ',', '.') }}</p>
                <p class="font-medium text-[#C2C2C2]">Total lembar telah dicetak</p>
            </div>
            <div class="p-2 bg-[#96befe] rounded-3xl h-fit">
                <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24">
                    <g fill="none" stroke="#174fa8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path d="M8 5H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5.697M18 14v4h4m-4-7V7a2 2 0 0 0-2-2h-2" />
                        <path
                            d="M8 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m6 13a4 4 0 1 0 8 0a4 4 0 1 0-8 0m-6-7h4m-4 4h3" />
                    </g>
                </svg>
            </div>
        </div>
        <div id="chartDashboard" class="col-span-3 row-span-6 row-start-4 bg-white shadow-md rounded-xl">
        </div>
        {{-- <div class="col-span-3 row-span-3 col-start-1 row-start-7 bg-white shadow-md rounded-xl p-6">8</div> --}}
        <div class="col-span-2 row-span-4 col-start-4 row-start-6 bg-white shadow-md rounded-xl p-6">
            <div class="flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                    <g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m15 16l-2.414-2.414A2 2 0 0 1 12 12.172V6" />
                    </g>
                </svg>
                <p class="font-medium">File Terbaru</p>
            </div>
            <div class="flex flex-col gap-0.5">
                @forelse($recentTransactions as $trx)
                    <div class="flex justify-between items-center mt-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <p class="font-bold text-red-600">
                            {{ strtoupper(pathinfo($trx->filename_snapshot, PATHINFO_EXTENSION)) ?: 'FILE' }}
                        </p>
                        <div class="flex flex-col flex-1 px-4 truncate">
                            <p class="truncate text-sm font-medium" title="{{ $trx->filename_snapshot }}">
                                {{ $trx->filename_snapshot }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $trx->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="p-1 px-2 bg-green-100 rounded-md text-green-800 text-xs font-bold shrink-0">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-10 text-gray-400 text-sm italic">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
        <a href={{ route('outlet.payments') }}
            class="col-span-2 row-span-2 col-start-4 row-start-4 bg-white shadow-md rounded-xl p-6 hover:bg-yellow-50 transition flex justify-between items-end">
            <div>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ $pendingCount }}</p>
                <p class="text-sm font-medium text-gray-400">Antrian Bayar</p>
            </div>
            <span
                class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded h-fit flex items-center gap-1">VERIFIKASI
                SEKARANG <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(90)"" width="15px" height="15px"
                    viewBox="0 0 24 24">
                    <path fill="none" stroke="#9F7208" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M12 5v14m6-8l-6-6m-6 6l6-6" />
                </svg></span>
        </a>
        <div class="row-span-6 col-start-6 row-start-4 bg-white shadow-md rounded-xl p-6 flex flex-col items-center">
            @if ($outlet->qris_path)
                <img src="{{ asset('storage/' . $outlet->qris_path) }}"
                    class="h-32 w-auto object-contain rounded shadow-sm mb-3">
                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">QRIS AKTIF</span>
            @else
                <div
                    class="h-32 w-32 flex items-center justify-center text-gray-300 border-2 border-dashed border-gray-200 rounded-lg mb-3">
                    No QRIS
                </div>
                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">BELUM DISET</span>
            @endif

            <p class="text-xs mt-4">Upload QRIS toko Anda agar pelanggan bisa membayar secara mandiri.</p>

            <a href="{{ route('outlet.editQRIS') }}"
                class="block w-full text-center bg-indigo-600 font-bold py-2 rounded-lg hover:bg-indigo-500 text-white mt-4">
                GANTI QRIS
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        var tanggal = @json($chartDates);
        var pemasukan = @json($chartRevenue);
        var lembar = @json($chartPages);

        var options = {
            series: [{
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
                },
                zoom: {
                    enabled: false
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
            yaxis: [{
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
                    formatter: function(val, {
                        seriesIndex
                    }) {
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
@endsection
