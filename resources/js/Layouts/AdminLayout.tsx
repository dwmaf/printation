import React, { useState, useEffect, ReactNode } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { LayoutDashboard, LogOut, FileCheck, Menu, X } from 'lucide-react';

interface SharedProps {
    pendingCount?: number;
    [key: string]: any;
}

interface AdminLayoutProps {
    children: ReactNode;
    header?: ReactNode;
}

export default function AdminLayout({ children, header }: AdminLayoutProps) {
    const { url, props } = usePage<{ props: SharedProps }>();
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    
    // Explicitly cast or access safely
    const pendingCount = (props as SharedProps).pendingCount || 0;

    // useEffect(() => {
    //     if ((window as any).Echo) {
    //         (window as any).Echo.channel('admin-upa-channel')
    //             .listen('.transaction.created', (e: any) => {
    //                 router.reload({ 
    //                     only: ['pendingCount'], 
    //                     preserveScroll: true 
    //                 });
    //             })
    //             .listen('.transaction.updated', (e: any) => {
    //                 router.reload({ 
    //                     only: ['pendingCount'], 
    //                     preserveScroll: true 
    //                 });
    //             });
    //     }
    // }, []);

    return (
        <div className="flex flex-col lg:flex-row p-3 sm:p-6 gap-3 sm:gap-4 min-h-screen bg-gray-100 font-roboto">
            {/* MOBILE HEADER with hamburger */}
            <div className="lg:hidden bg-white rounded-xl p-4 flex justify-between items-center shadow-sm sticky top-3 z-50">
                <div className="flex items-center gap-2">
                    <img src="/images/logo.png" alt="Logo" className="w-8 h-8 object-contain" />
                    <h1 className="font-koulen text-xl text-gray-800 tracking-wide">Printation</h1>
                </div>
                <button onClick={() => setMobileMenuOpen(!mobileMenuOpen)} className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    {!mobileMenuOpen ? (
                        <Menu className="w-6 h-6 text-gray-600" />
                    ) : (
                        <X className="w-6 h-6 text-gray-600" />
                    )}
                </button>
            </div>

            {/* MOBILE MENU OVERLAY */}
            {mobileMenuOpen && (
                <div 
                    className="lg:hidden fixed inset-0 bg-black/50 z-40 top-18"
                    onClick={() => setMobileMenuOpen(false)}
                ></div>
            )}

            {/* SIDEBAR */}
            <div className={`bg-white rounded-xl flex flex-col shadow-sm shrink-0 transition-all duration-300 ${
                mobileMenuOpen ? 'fixed top-18 left-3 right-3 z-40 max-h-[calc(100vh-90px)]' : 'hidden'
            } lg:flex lg:sticky lg:w-[18%] lg:h-[calc(100vh-3rem)] lg:top-6`}
            >
                
                {/* Desktop Logo (hidden on mobile) */}
                <div className="hidden lg:flex items-center mb-8 p-6 gap-2">
                    <img src="/images/logo.png" alt="Logo" className="w-10 h-10 object-contain" />
                    <h1 className="font-koulen text-3xl text-gray-800 tracking-wide">Printation</h1>
                </div>

                <h2 className="font-bold text-gray-400 mb-2 ml-6 mt-4 lg:mt-0 text-sm uppercase tracking-wider">Menu</h2>

                <div className="flex flex-col">
                    <Link href="/admin/upa/dashboard"
                        onClick={() => setMobileMenuOpen(false)}
                        className={`flex gap-3 items-center cursor-pointer p-3 pl-6 w-full transition-colors font-medium border-r-4 ${
                            url.startsWith('/admin/upa/dashboard') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-400 border-transparent hover:bg-gray-50 hover:text-indigo-500'
                        }`}>
                        <LayoutDashboard className="w-5 h-5" />
                        Dashboard
                    </Link>

                    <Link href="/admin/upa/verify-print"
                        onClick={() => setMobileMenuOpen(false)}
                        className={`flex gap-3 items-center cursor-pointer p-3 pl-6 w-full transition-colors font-medium border-r-4 ${
                            url.startsWith('/admin/upa/verify-print') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-400 border-transparent hover:bg-gray-50 hover:text-indigo-500'
                        }`}>
                        <FileCheck className="w-5 h-5" />
                        <span className="flex-1">Verify Print</span>
                        {pendingCount > 0 && (
                            <span className="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full mr-2 shadow-sm">{pendingCount}</span>
                        )}
                    </Link>
                </div>

                <Link href="/logout" method="post" as="button"
                    className="mt-4 lg:mt-auto text-red-500 w-full p-4 pl-6 flex hover:bg-red-50 transition-colors cursor-pointer rounded-b-xl gap-3 items-center font-medium mb-4 lg:mb-0">
                    <LogOut className="w-5 h-5" />
                    Logout
                </Link>
            </div>

            {/* MAIN CONTENT */}
            <div className="flex flex-col flex-1 min-w-0">
                {/* HEADER (Desktop only - mobile has header in hamburger section) */}
                <div
                    className="hidden lg:flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-white p-6 rounded-xl shadow-sm shrink-0">
                    <div>
                        {header || (
                            <h1 className="text-3xl text-gray-800 font-koulen uppercase tracking-wide">Dashboard</h1>
                        )}
                    </div>
                    <div className="flex items-center gap-3">
                        <img src="/images/upa-pkk-logo.jpg" alt="logo_upa_pkk"
                            className="w-12 h-12 rounded-full border border-gray-100" />
                        <div className="flex flex-col">
                            <p className="font-bold text-gray-800">UPA PKK UNTAN</p>
                            <p className="text-sm text-gray-400">Super administrator</p>
                        </div>
                    </div>
                </div>

                {/* MOBILE HEADER INFO */}
                <div className="lg:hidden bg-white p-4 rounded-xl shadow-sm mb-3 flex items-center justify-between">
                    <div>
                        {header || (
                            <h1 className="text-xl text-gray-800 font-koulen uppercase tracking-wide">Dashboard</h1>
                        )}
                    </div>
                    <img src="/images/upa-pkk-logo.jpg" alt="logo_upa_pkk"
                        className="w-10 h-10 rounded-full border border-gray-100" />
                </div>

                {/* DYNAMIC CONTENT */}
                <div className="h-full">
                    {children}
                </div>
            </div>
        </div>
    );
}
