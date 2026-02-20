<script setup>
import { Head } from '@inertiajs/vue3';
import { Printer, TrendingUp } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Hardcoded data for now
const printedSheetsString = "12,450";
const trendPercentage = "8%";
</script>

<template>

    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <h1 class="text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                Dashboard
            </h1>
        </template>

        <div class="h-full">
            <div class="grid grid-cols-6 grid-rows-6 gap-4 h-full">

                <!-- KERTAS TERPRINT CARD -->
                <!-- Placing it in the first slot for visibility since it's the only one requested -->
                <div
                    class="col-span-2 row-span-2 shadow-md rounded-xl p-6 flex flex-col justify-between bg-white relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-between items-start z-10">
                        <div>
                            <p class="font-bold text-lg text-gray-700">Total Lembar Terprint</p>
                            <p class="font-medium text-xs text-gray-400">Bulan Ini</p>
                        </div>
                        <div class="p-2 bg-blue-100/50 rounded-2xl group-hover:scale-110 transition-transform">
                            <Printer class="w-6 h-6 text-blue-600" />
                        </div>
                    </div>

                    <div class="z-10">
                        <p class="text-4xl font-extrabold text-gray-800 mb-2 mt-2">{{ printedSheetsString }}</p>
                        <div class="flex items-center gap-2">
                            <div class="bg-green-100 p-0.5 rounded-full">
                                <TrendingUp class="w-3 h-3 text-green-600" />
                            </div>
                            <span class="text-green-500 font-bold text-sm">{{ trendPercentage }}</span>
                            <span class="text-xs text-gray-400">dari bulan lalu</span>
                        </div>
                    </div>

                    <!-- Decorative gradient/shape -->
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50">
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
