import React, { useState, useEffect } from 'react';
import { Head, useForm, router, Link } from '@inertiajs/react';
import { Search, Check, X, File, ChevronLeft, ChevronRight } from 'lucide-react';
import { route } from 'ziggy-js';
import Pagination from '@/Components/Pagination';
import AdminLayout from '@/Layouts/AdminLayout';

interface PrintRequestData {
    id: number;
    request_id: string;
    status: string;
    calculated_pages: number;
    copies: number;
    color_mode: string;
    paper_size: string | null;
}

interface PrintRequestsPaginated {
    data: PrintRequestData[];
    from: number;
    to: number;
    total: number;
    links: any[];
}

interface Filters {
    search: string;
}

interface VerifyPrintProps {
    printrequests: PrintRequestsPaginated;
    filters: Filters;
}

export default function VerifyPrint({ printrequests, filters }: VerifyPrintProps) {
    const [search, setSearch] = useState(filters.search || '');
    const { post } = useForm();

    // Query Search (Debounce agar tidak terlalu berat)
    useEffect(() => {
        const timeoutId = setTimeout(() => {
            router.get(route('admin.upa.verify-print.index'), { search }, {
                preserveState: true,
                replace: true
            });
        }, 300); // 300ms debounce
        
        return () => clearTimeout(timeoutId);
    }, [search]);

    // useEffect(() => {
    //     if ((window as any).Echo) {
    //         (window as any).Echo.channel('admin-upa-channel')
    //             .listen('.transaction.created', (e: any) => {
    //                 console.log('New transaction created, refreshing...');
    //                 router.reload({ preserveScroll: true });
    //             })
    //             .listen('.transaction.updated', (e: any) => {
    //                 console.log('Transaction updated, refreshing...');
    //                 router.reload({ preserveScroll: true });
    //             });
    //     }
    // }, []);

    const getStatusStyle = (status: string) => {
        switch (status) {
            case 'pending': return 'bg-yellow-50 text-yellow-600';
            case 'completed': return 'bg-green-50 text-green-600';
            case 'rejected': return 'bg-red-50 text-red-600';
            default: return 'bg-gray-100 text-gray-600';
        }
    };

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'pending': return 'PENDING';
            case 'rejected': return 'DITOLAK';
            default: return status.toUpperCase();
        }
    };

    const verify = (id: number) => {
        if (confirm('Verifikasi order ini?')) {
            post(route('admin.upa.verify-print.verify', id));
        }
    };

    const reject = (id: number) => {
        if (confirm('Tolak order ini?')) {
            post(route('admin.upa.verify-print.reject', id));
        }
    };

    return (
        <AdminLayout
            header={
                <h1 className="text-xl sm:text-2xl md:text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                    Verifikasi Print
                </h1>
            }
        >
            <Head title="Verifikasi Print" />

            {/* KARTU UTAMA */}
            <div className="bg-white rounded-xl sm:rounded-[20px] shadow-sm border border-gray-100 flex-1 flex flex-col p-3 sm:p-6 md:p-8 h-full">

                {/* Judul & Search */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-8 gap-3 md:gap-4">
                    <h2 className="text-2xl sm:text-2xl font-bold text-black">Daftar File</h2>

                    {/* Search Input */}
                    <div className="relative w-full md:w-64">
                        <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <Search className="w-5 h-5" />
                        </span>

                        <input 
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            type="text" 
                            placeholder="Cari Order ID / File..."
                            className="w-full bg-[#FAFAFA] text-gray-700 text-sm rounded-lg border-none focus:ring-2 focus:ring-indigo-200 focus:bg-white py-2.5 pl-10 pr-4 transition-all" />
                    </div>
                </div>

                {/* TABLE - Desktop View (hidden on mobile) */}
                <div className="hidden lg:block overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="text-[#8E8D8D] text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                                <th className="py-4 pr-4">ID</th>
                                <th className="py-4 px-4">STATUS</th>
                                <th className="py-4 px-4">HALAMAN</th>
                                <th className="py-4 px-4">Copy</th>
                                <th className="py-4 px-4 text-center">WARNA</th>
                                <th className="py-4 px-4 text-center">UKURAN</th>
                                <th className="py-4 px-4 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm text-gray-600 divide-y divide-gray-50">
                            {printrequests.data.map((printrequest) => (
                                <tr key={printrequest.id} className="group hover:bg-gray-50 transition-colors">
                                    {/* ID */}
                                    <td className="py-5 pr-4 font-semibold text-gray-900">
                                        {printrequest.request_id}
                                    </td>

                                    {/* STATUS */}
                                    <td className="py-5 px-4">
                                        <span className={`px-3 py-1 rounded-md text-[10px] font-bold ${getStatusStyle(printrequest.status)}`}>
                                            {getStatusLabel(printrequest.status)}
                                        </span>
                                    </td>

                                    {/* HALAMAN */}
                                    <td className="py-5 px-4">
                                        {printrequest.calculated_pages} Hal
                                    </td>

                                    {/* Copies */}
                                    <td className="py-5 px-4">
                                        {printrequest.copies} Copy
                                    </td>

                                    {/* WARNA */}
                                    <td className="py-5 px-4 text-center">
                                        {printrequest.color_mode === 'color' ? (
                                            <Check className="w-5 h-5 text-gray-500 mx-auto" strokeWidth="2" />
                                        ) : (
                                            <X className="w-5 h-5 text-gray-300 mx-auto" strokeWidth="2" />
                                        )}
                                    </td>

                                    {/* UKURAN */}
                                    <td className="py-5 px-4 text-center font-medium">
                                        {printrequest.paper_size?.toUpperCase()}
                                    </td>

                                    {/* AKSI */}
                                    <td className="py-5 px-4 text-right">
                                        {printrequest.status === 'pending' ? (
                                            <div className="flex justify-end gap-2">
                                                <button onClick={() => verify(printrequest.id)}
                                                    className="w-8 h-8 rounded bg-[#4ADE80] text-white flex items-center justify-center hover:bg-green-500 transition shadow-sm cursor-pointer">
                                                    <Check className="w-4 h-4" strokeWidth="3" />
                                                </button>
                                                <button onClick={() => reject(printrequest.id)}
                                                    className="w-8 h-8 rounded bg-[#FB7185] text-white flex items-center justify-center hover:bg-red-500 transition shadow-sm cursor-pointer">
                                                    <X className="w-4 h-4" strokeWidth="3" />
                                                </button>
                                            </div>
                                        ) : printrequest.status === 'completed' ? (
                                            <div className="flex justify-end">
                                                <span className="flex items-center gap-1 text-indigo-500 font-bold text-xs bg-indigo-50 px-2 py-1 rounded">
                                                    <Check className="w-3 h-3" strokeWidth="4" /> TERPRINT
                                                </span>
                                            </div>
                                        ) : (
                                            <span className="text-gray-300 text-sm italic block w-fit ml-auto">No Action</span>
                                        )}
                                    </td>
                                </tr>
                            ))}

                            {/* Empty State Example */}
                            {printrequests.data.length === 0 && (
                                <tr>
                                    <td colSpan={7} className="text-center py-10 text-gray-400">
                                        Tidak ada data.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* MOBILE CARD VIEW */}
                <div className="lg:hidden space-y-3">
                    {/* Empty State */}
                    {printrequests.data.length === 0 && (
                        <div className="text-center py-10 text-gray-400">
                            Tidak ada data.
                        </div>
                    )}

                    {/* Card for each print request */}
                    {printrequests.data.map((printrequest) => (
                        <div key={printrequest.id} className="bg-white border-2 border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">

                            {/* Header: ID & Status */}
                            <div className="flex justify-between items-center mb-3">
                                <div className="font-bold text-gray-900 text-lg">{printrequest.request_id}</div>
                                <span className={`px-3 py-1.5 rounded-lg text-xs font-bold ${getStatusStyle(printrequest.status)}`}>
                                    {getStatusLabel(printrequest.status)}
                                </span>
                            </div>

                            {/* Compact Info Row */}
                            <div className="flex items-center justify-between text-sm text-gray-600 mb-3 pb-3 border-b border-gray-100">
                                <div className="flex items-center gap-4">
                                    <span className="font-semibold text-gray-900">
                                        {printrequest.calculated_pages} hal
                                    </span>
                                    <span className="text-gray-400">•</span>
                                    <span className="font-semibold text-gray-900">
                                        {printrequest.copies} Copy
                                    </span>
                                </div>
                            </div>

                            {/* Second Row: Color & Size */}
                            <div className="flex items-center justify-between mb-3.5">
                                <div className="flex items-center gap-2">
                                    {printrequest.color_mode === 'color' ? (
                                        <Check className="w-5 h-5 text-green-600" strokeWidth="2.5" />
                                    ) : (
                                        <X className="w-5 h-5 text-gray-400" strokeWidth="2.5" />
                                    )}
                                    <span className="text-sm font-semibold text-gray-900">
                                        {printrequest.color_mode === 'color' ? 'Berwarna' : 'Hitam Putih'}
                                    </span>
                                </div>
                                <div className="bg-gray-100 px-3 py-1.5 rounded-md">
                                    <span className="font-bold text-gray-900 text-sm">
                                        {printrequest.paper_size?.toUpperCase()}
                                    </span>
                                </div>
                            </div>

                            {/* Action Buttons */}
                            {printrequest.status === 'pending' ? (
                                <div className="flex gap-2.5">
                                    <button onClick={() => verify(printrequest.id)}
                                        className="flex-1 bg-[#4ADE80] text-white py-3.5 px-4 rounded-lg font-bold text-base hover:bg-green-500 transition shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                        <Check className="w-5 h-5" strokeWidth="3" />
                                        Verifikasi
                                    </button>
                                    <button onClick={() => reject(printrequest.id)}
                                        className="flex-1 bg-[#FB7185] text-white py-3.5 px-4 rounded-lg font-bold text-base hover:bg-red-500 transition shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                        <X className="w-5 h-5" strokeWidth="3" />
                                        Tolak
                                    </button>
                                </div>
                            ) : (
                                <div className="text-center text-gray-400 py-2 text-sm font-medium">
                                    Tidak ada aksi
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                {/* Pagination */}
                <div className="mt-auto pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-400 gap-3">
                    <div className="text-center sm:text-left">
                        Showing {printrequests.from} to {printrequests.to} of {printrequests.total} entries
                    </div>
                    <Pagination links={printrequests.links} />
                </div>

            </div>
        </AdminLayout>
    );
}