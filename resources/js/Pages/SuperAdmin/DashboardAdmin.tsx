import React from 'react';
import { Head } from '@inertiajs/react';
import { Printer, TrendingUp, TrendingDown, BarChart3 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import Chart from 'react-apexcharts';

interface StatData {
    month: string;
    total: number;
}

interface StatsProps {
    stats: {
        chartData: StatData[];
        sheetsThisMonth: string | number;
        trendPercentage: string | number;
        sheetsAllTime: string | number;
    }
}

export default function DashboardAdmin({ stats }: StatsProps) {
    const isTrendPositive = parseFloat(stats.trendPercentage.toString()) >= 0;

    const chartOptions = {
        chart: {
            type: 'bar' as const,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: { borderRadius: 4, columnWidth: '50%' }
        },
        dataLabels: { enabled: false },
        colors: ['#4F46E5'],
        xaxis: { categories: stats.chartData.map(d => d.month) }
    };

    const chartSeries = [{
        name: 'Total Lembar',
        data: stats.chartData.map(d => d.total)
    }];

    return (
        <AdminLayout
            header={
                <h1 className="text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                    Dashboard
                </h1>
            }
        >
            <Head title="Admin Dashboard" />

            <div className="h-full">
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                    {/* KERTAS TERPRINT CARD bulanan*/}                
                    <div className="md:col-span-1 xl:col-span-2 shadow-md rounded-xl p-5 sm:p-6 flex flex-col justify-between bg-white relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                        <div className="flex justify-between items-start z-10">
                            <div>
                                <p className="font-bold text-lg text-gray-700">Total Lembar Terprint</p>
                                <p className="font-medium text-xs text-gray-400">Bulan Ini</p>
                            </div>
                            <div className="p-2 bg-blue-100/50 rounded-2xl group-hover:scale-110 transition-transform">
                                <Printer className="w-6 h-6 text-blue-600" />
                            </div>
                        </div>

                        <div className="z-10">
                            <p className="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-2 mt-2 leading-tight">
                                <span>{stats.sheetsThisMonth}</span>
                                <span className="block sm:inline sm:ml-1">Lembar</span>
                            </p>
                            <div className="flex items-center gap-2">
                                <div className={`p-0.5 rounded-full ${isTrendPositive ? 'bg-green-100' : 'bg-red-100'}`}>
                                    {isTrendPositive ? (
                                        <TrendingUp className="w-3 h-3 text-green-600" />
                                    ) : (
                                        <TrendingDown className="w-3 h-3 text-red-600" />
                                    )}
                                </div>
                                <span className={`font-bold text-sm ${isTrendPositive ? 'text-green-500' : 'text-red-500'}`}>
                                    {stats.trendPercentage}
                                </span>
                                <span className="text-xs text-gray-400">dari bulan lalu</span>
                            </div>
                        </div>

                        {/* Decorative gradient/shape */}
                        <div className="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50">
                        </div>
                    </div>

                    {/* TOTAL LEMBAR SEPANJANG MASA CARD */}
                    <div className="md:col-span-1 xl:col-span-2 shadow-md rounded-xl p-5 sm:p-6 flex flex-col justify-between bg-white relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                        <div className="flex justify-between items-start z-10">
                            <div>
                                <p className="font-bold text-lg text-gray-700">Total Terprint</p>
                                <p className="font-medium text-xs text-gray-400">Sepanjang Masa</p>
                            </div>
                            <div className="p-2 bg-purple-100/50 rounded-2xl group-hover:scale-110 transition-transform">
                                <Printer className="w-6 h-6 text-purple-600" />
                            </div>
                        </div>

                        <div className="z-10">
                            <p className="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-2 mt-2 leading-tight">
                                <span>{stats.sheetsAllTime}</span>
                                <span className="block sm:inline sm:ml-1">Lembar</span>
                            </p>
                            <div className="flex items-center gap-2">
                                <span className="text-xs text-gray-400 font-medium italic">Akumulasi seluruh kertas terprint</span>
                            </div>
                        </div>

                        {/* Decorative shape */}
                        <div className="absolute -bottom-4 -right-4 w-24 h-24 bg-purple-50 rounded-full blur-2xl opacity-50">
                        </div>
                    </div>

                    {/* apex charts */}
                    <div className="md:col-span-2 xl:col-span-4 bg-white shadow-md rounded-xl p-4 sm:p-6">
                        <div className="flex items-center gap-2 mb-6">
                            <BarChart3 className="w-5 h-5 text-gray-400" />
                            <h3 className="font-bold text-gray-700">Statistik Pencetakan (6 Bulan Terakhir)</h3>
                        </div>
                        <Chart 
                            type="bar" 
                            height="280" 
                            options={chartOptions} 
                            series={chartSeries} 
                        />
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}