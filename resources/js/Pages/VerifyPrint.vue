<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { Search, Check, X, File, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    printrequests: Array,
});

onMounted(() => {
    if (window.Echo) {
        // Listen ke channel khusus admin agar semua update dari semua station ketarik
        window.Echo.channel('admin-upa-channel')
            .listen('.transaction.created', (e) => {
                console.log('New transaction created, refreshing...');
                router.reload({ preserveScroll: true });
            })
            .listen('.transaction.updated', (e) => {
                console.log('Transaction updated, refreshing...');
                router.reload({ preserveScroll: true });
            });
    }
});

const getStatusStyle = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-600';
        case 'processing': return 'bg-green-50 text-green-600';
        case 'rejected': return 'bg-red-50 text-red-600';
        case 'completed': return 'bg-blue-50 text-blue-600';
        default: return 'bg-gray-100 text-gray-600';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'pending': return 'PENDING';
        case 'processing': return 'DITERIMA';
        case 'rejected': return 'DITOLAK';
        default: return status.toUpperCase();
    }
};

const verify = (id) => {
    if (confirm('Verifikasi order ini?')) {
        useForm({}).post(route('admin.upa.verify-print.verify', id));
    }
};

const reject = (id) => {
    if (confirm('Tolak order ini?')) {
        useForm({}).post(route('admin.upa.verify-print.reject', id));
    }
};

</script>

<template>

    <Head title="Verifikasi Print" />

    <AdminLayout>
        <template #header>
            <h1 class="text-3xl text-gray-800 font-koulen uppercase tracking-wide">
                Verifikasi Print
            </h1>
        </template>

        <!-- KARTU UTAMA -->
        <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 flex-1 flex flex-col p-8 h-full">

            <!-- Judul & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h2 class="text-2xl font-bold text-black">Daftar File</h2>

                <!-- Search Input -->
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <Search class="w-5 h-5" />
                    </span>

                    <input type="text" placeholder="Cari Order ID / File..."
                        class="w-full bg-[#FAFAFA] text-gray-700 text-sm rounded-lg border-none focus:ring-2 focus:ring-indigo-200 focus:bg-white py-2.5 pl-10 pr-4 transition-all">
                </div>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[#8E8D8D] text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 pr-4">ID</th>
                            <th class="py-4 px-4">STATUS</th>
                            <th class="py-4 px-4">HALAMAN</th>
                            <th class="py-4 px-4">JUMLAH</th>
                            <th class="py-4 px-4 text-center">WARNA</th>
                            <th class="py-4 px-4 text-center">UKURAN</th>
                            <th class="py-4 px-4 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                        <tr v-for="printrequest in printrequests" :key="printrequest.id"
                            class="group hover:bg-gray-50 transition-colors">
                            <!-- ID -->
                            <td class="py-5 pr-4 font-semibold text-gray-900">
                                {{ printrequest.request_id }}
                            </td>

                            <!-- STATUS -->
                            <td class="py-5 px-4">
                                <span class="px-3 py-1 rounded-md text-[10px] font-bold"
                                    :class="getStatusStyle(printrequest.status)">
                                    {{ getStatusLabel(printrequest.status) }}
                                </span>
                            </td>

                            <!-- HALAMAN -->
                            <td class="py-5 px-4">
                                {{ printrequest.detected_pages }} / {{ printrequest.calculated_pages }} Hal
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-5 px-4">
                                {{ printrequest.copies }} lembar
                            </td>

                            <!-- WARNA -->
                            <td class="py-5 px-4 text-center">
                                <Check v-if="printrequest.color_mode === 'color'" class="w-5 h-5 text-gray-500 mx-auto"
                                    stroke-width="2" />
                                <X v-else class="w-5 h-5 text-gray-300 mx-auto" stroke-width="2" />
                            </td>

                            <!-- UKURAN -->
                            <td class="py-5 px-4 text-center font-medium">
                                {{ printrequest.paper_size?.toUpperCase() }}
                            </td>

                            <!-- AKSI -->
                            <td class="py-5 px-4 text-right">
                                <div v-if="printrequest.status === 'pending'" class="flex justify-end gap-2">
                                    <button @click="verify(printrequest.id)"
                                        class="w-6 h-6 rounded bg-[#4ADE80] text-white flex items-center justify-center hover:bg-green-500 transition shadow-sm cursor-pointer">
                                        <Check class="w-4 h-4" stroke-width="3" />
                                    </button>
                                    <button @click="reject(printrequest.id)"
                                        class="w-6 h-6 rounded bg-[#FB7185] text-white flex items-center justify-center hover:bg-red-500 transition shadow-sm cursor-pointer">
                                        <X class="w-4 h-4" stroke-width="3" />
                                    </button>
                                </div>
                                <span v-else class="text-gray-300 text-lg mx-auto block w-fit">-</span>
                            </td>
                        </tr>

                        <!-- Empty State Example (Hidden if data exists) -->
                        <tr v-if="printrequests.length === 0">
                            <td colspan="8" class="text-center py-10 text-gray-400">
                                Tidak ada data.
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-auto pt-6 flex justify-between items-center text-xs text-gray-400">
                <div>Showing 1 to {{ printrequests.length }} of {{ printrequests.length }} entries</div>
                <div class="flex gap-1">
                    <button
                        class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center hover:bg-gray-200 cursor-pointer">
                        <ChevronLeft class="w-4 h-4" />
                    </button>
                    <button
                        class="w-6 h-6 bg-white border rounded flex items-center justify-center font-bold text-black cursor-pointer">1</button>
                    <button
                        class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center hover:bg-gray-200 cursor-pointer">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>