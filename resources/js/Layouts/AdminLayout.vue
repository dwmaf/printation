<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, BadgeCheck, LogOut, FileCheck } from 'lucide-vue-next';

// In a real app we might get the user info from shared props
const page = usePage();
</script>

<template>
    <div class="flex p-6 gap-4 min-h-screen bg-gray-100 font-roboto">
        <!-- SIDEBAR -->
        <div class="bg-white rounded-xl flex flex-col w-[18%] shadow-sm shrink-0 h-[calc(100vh-3rem)] sticky top-6">
            <div class="flex items-center mb-8 p-6 gap-2">
                <img src="/images/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                <h1 class="font-koulen text-3xl text-gray-800 tracking-wide">Printation</h1>
            </div>

            <h2 class="font-bold text-gray-400 mb-2 ml-6 text-sm uppercase tracking-wider">Menu</h2>

            <div class="flex flex-col">
                <Link href="/admin/upa/dashboard"
                    class="flex gap-3 items-center cursor-pointer p-3 pl-6 w-full transition-colors font-medium border-r-4"
                    :class="$page.url.startsWith('/admin/upa/dashboard') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-400 border-transparent hover:bg-gray-50 hover:text-indigo-500'">
                    <LayoutDashboard class="w-5 h-5" />
                    Dashboard
                </Link>

                <Link href="/admin/upa/verify-print"
                    class="flex gap-3 items-center cursor-pointer p-3 pl-6 w-full transition-colors font-medium border-r-4"
                    :class="$page.url.startsWith('/admin/upa/verify-print') ? 'text-indigo-600 bg-indigo-50 border-indigo-600' : 'text-gray-400 border-transparent hover:bg-gray-50 hover:text-indigo-500'">
                    <FileCheck class="w-5 h-5" />
                    <span class="flex-1">Verify Print</span>
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full mr-2 shadow-sm">3</span>
                </Link>
            </div>

            <Link href="/logout" method="post" as="button"
                class="mt-auto text-red-500 w-full p-4 pl-6 flex hover:bg-red-50 transition-colors cursor-pointer rounded-b-xl gap-3 items-center font-medium">
                <LogOut class="w-5 h-5" />
                Logout
            </Link>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex flex-col flex-1 min-w-0">
            <!-- HEADER -->
            <div
                class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-white p-6 rounded-xl shadow-sm shrink-0">
                <div>
                    <slot name="header">
                        <h1 class="text-3xl text-gray-800 font-koulen uppercase tracking-wide">Dashboard</h1>
                    </slot>
                </div>
                <div class="flex items-center gap-3">
                    <img src="/images/upa-pkk-logo.jpg" alt="logo_upa_pkk"
                        class="w-12 h-12 rounded-full border border-gray-100">
                    <div class="flex flex-col">
                        <p class="font-bold text-gray-800">UPA PKK UNTAN</p>
                        <p class="text-sm text-gray-400">Super administrator</p>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC CONTENT -->
            <div class="h-full">
                <slot />
            </div>
        </div>
    </div>
</template>
