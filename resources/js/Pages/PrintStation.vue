<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Trash2, Printer, FileText, FileImage, CreditCard, Clock, CheckCircle, XCircle, Eye, EyeOff } from 'lucide-vue-next';

// PROPS
const props = defineProps({
    filetoprints: Array,
    qrCode: String,
    stationId: Number,
});

import axios from 'axios';

// STATE
const modalOpen = ref(false);
const deleteModalOpen = ref(false);
const bulkDeleteModalOpen = ref(false);
const qrModalOpen = ref(false);
const loading = ref(false);

const currentFile = ref(null);
const fileToDelete = ref(null);
const selectedIds = ref([]);
const showQr = ref(props.filetoprints.length === 0);
// Pantau perubahan jumlah file
watch(() => props.filetoprints.length, (newLength) => {
    if (newLength === 0) {
        showQr.value = true; // Munculkan otomatis jika kosong
    } else {
        showQr.value = false; // Sembunyikan otomatis jika ada file baru masuk
    }
});

const config = ref({
    copies: 1,
    colorMode: 'color', // color, bw
    paperSize: 'A4',
    pageOption: 'all', // all, custom
    customPages: '',
    pages: 1, // Detected pages
});

// COMPUTED
const isImage = computed(() => {
    if (!currentFile.value) return false;
    const type = currentFile.value.type.toUpperCase();
    return ['JPG', 'JPEG', 'PNG', 'WEBP'].includes(type);
});

const countCustomPages = (rangeStr) => {
    if (!rangeStr) return 0;
    const parts = rangeStr.replace(/\s/g, '').split(',');
    let count = 0;
    parts.forEach(part => {
        if (part.includes('-')) {
            const [start, end] = part.split('-').map(Number);
            if (start && end && end >= start) count += (end - start + 1);
        } else if (part !== '') {
            count++;
        }
    });
    return count;
};

const openPrintModal = async (file) => {
    currentFile.value = file;

    // If there's an existing latest_print_request, use its columns
    if (file.latest_print_request) {
        const v = file.latest_print_request;
        config.value = {
            copies: v.copies || 1,
            colorMode: v.color_mode || 'color',
            paperSize: v.paper_size || 'A4',
            pageOption: v.page_range === 'all' ? 'all' : 'custom',
            customPages: v.page_range === 'all' ? '' : v.page_range,
            pages: v.detected_pages || 1,
        };
    } else {
        config.value = {
            copies: 1,
            colorMode: 'color',
            paperSize: 'A4',
            pageOption: 'all',
            customPages: '',
            pages: 1,
        };
    }

    modalOpen.value = true;

    // Detect pages for PDF if not already detected
    if (file.type === 'PDF' && window.PDFLib && !file.latest_print_request) {
        try {
            const existingPdfBytes = await fetch(file.url).then(res => res.arrayBuffer());
            const pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);
            config.value.pages = pdfDoc.getPageCount();
        } catch (e) {
            console.error("Failed to count pages", e);
        }
    } else if (file.type !== 'PDF' && !file.latest_print_request) {
        config.value.pages = 1;
    }
};

const closePrintModal = () => {
    modalOpen.value = false;
    currentFile.value = null;
};

const submitRequest = () => {
    const form = useForm({
        file_id: currentFile.value.id,
        station_id: props.stationId,
        print_config: {
            copies: config.value.copies,
            color: config.value.colorMode,
            paper: config.value.paperSize,
            pages: config.value.pageOption === 'custom' ? config.value.customPages : 'all',
            detected_pages: config.value.pages
        }
    });

    form.post(route('upa.station.verify-request'), {
        onSuccess: () => {
            modalOpen.value = false;
        },
        onFinish: () => router.reload(),
    });
};

const executePrint = async () => {
    if (!currentFile.value || !currentFile.value.latest_print_request) return;

    loading.value = true;
    try {
        const response = await axios.post(route('upa.station.print'), {
            request_id: currentFile.value.latest_print_request.id,
        });

        if (response.data.status === 'success') {
            alert(response.data.message);
            closePrintModal();
            router.reload();
        } else {
            alert(response.data.message);
        }
    } catch (error) {
        console.error("Print failed", error);
        alert(error.response?.data?.message || "Gagal mengirim perintah cetak.");
    } finally {
        loading.value = false;
    }
};

const openDeleteModal = (file) => {
    fileToDelete.value = file;
    deleteModalOpen.value = true;
};

const confirmDelete = () => {
    if (fileToDelete.value) {
        router.delete(route('upa.station.destroy', fileToDelete.value.id), {
            onSuccess: () => {
                deleteModalOpen.value = false;
                fileToDelete.value = null;
            }
        });
    }
};

const deleteMultiple = () => {
    if (selectedIds.value.length === 0) return;
    bulkDeleteModalOpen.value = true;
};

const confirmBulkDelete = () => {
    router.delete(route('upa.station.destroy-multiple'), {
        data: { file_ids: selectedIds.value },
        onSuccess: () => {
            selectedIds.value = [];
            bulkDeleteModalOpen.value = false;
        },
        onError: () => {
            bulkDeleteModalOpen.value = false;
        }
    });
};

onMounted(() => {
    if (window.Echo) {
        window.Echo.channel(`printing-channel.${props.stationId}`)
            .listen('.file.uploaded', (e) => {
                console.log('File uploaded, refreshing...');
                router.reload({ preserveScroll: true });
            })
            .listen('.transaction.updated', (e) => {
                console.log('Status updated, refreshing...');
                router.reload({ preserveScroll: true });
            });
    }
});
</script>

<template>


    <div class="p-4 flex flex-col bg-[#FAFAFA] items-center justify-center font-roboto text-gray-800">

        <!-- EMPTY STATE -->
        <div class="flex flex-col items-center">
            <div class="flex items-center gap-3 mb-8" v-if="!filetoprints.length > 0">
                <img src="/images/logo.png" class="w-16 h-16" alt="Logo">
                <h1 class="text-5xl font-koulen text-gray-900">Printation</h1>
            </div>

            <div class="flex flex-col items-center mb-6">
                <h2 v-if="!filetoprints.length > 0" class="uppercase font-medium text-gray-400">{{
                    $page.props.auth.user.name }}</h2>
                <button @click="showQr = !showQr"
                    class="mt-3 flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-full transition-all border border-indigo-100">
                    <Eye v-if="!showQr" class="w-4 h-4" />
                    <EyeOff v-else class="w-4 h-4" />
                    {{ showQr ? 'Hide QR Card' : 'Show QR Card' }}
                </button>
            </div>

            <div v-if="showQr"
                class="w-fit bg-white p-8 rounded-2xl shadow-xl flex flex-col items-center animate-in zoom-in duration-300">
                <p class="text-gray-400 mb-6 font-medium">Scan QR untuk upload file</p>
                <div class="h-64 bg-white mb-6" v-html="qrCode"></div>

                <div class="flex items-center space-x-3 bg-gray-100 px-6 py-3 rounded-full">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                    <span class="text-sm font-bold text-gray-600">Menunggu file...</span>
                </div>
            </div>
        </div>

        <!-- FILE LIST -->
        <div v-if="filetoprints.length > 0" class="w-full h-full max-w-6xl flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <img src="/images/logo.png" class="w-12 h-12" alt="Logo">
                    <div>
                        <h1 class="text-4xl font-koulen text-gray-900 leading-none">Printation</h1>
                        <p class="text-sm text-gray-400 font-bold uppercase">{{ $page.props.auth.user.name }}</p>
                    </div>
                    <button v-if="selectedIds.length > 0" @click="deleteMultiple"
                        class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-full transition-all shadow-lg shadow-red-100 animate-in fade-in slide-in-from-right-4 duration-200">
                        <Trash2 class="w-4 h-4" />
                        Hapus {{ selectedIds.length }} File
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-gray-500 font-bold">{{ filetoprints.length }} file terupload</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex-1 flex flex-col">
                <div class="overflow-y-auto custom-scrollbar flex-1">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-100">
                            <tr>
                                <th class="p-4 text-center w-16">
                                    <input id="checkbox-all" type="checkbox"
                                        :checked="selectedIds.length === filetoprints.length && filetoprints.length > 0"
                                        @change="(e) => selectedIds = e.target.checked ? filetoprints.map(f => f.id) : []"
                                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="p-4 font-bold text-sm text-gray-500 uppercase">Tipe</th>
                                <th class="p-4 font-bold text-sm text-gray-500 uppercase">Nama File</th>
                                <th class="p-4 font-bold text-sm text-gray-500 uppercase">Waktu</th>
                                <th class="p-4 font-bold text-sm text-gray-500 uppercase text-center">Status</th>
                                <th class="p-4 font-bold text-sm text-gray-500 uppercase text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="file in filetoprints" :key="file.id"
                                class="hover:bg-blue-50/50 transition-colors">
                                <td class="p-4 text-center">
                                    <input id="checkbox" type="checkbox" v-model="selectedIds" :value="file.id"
                                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="p-4">
                                    <div class="font-black text-xs px-2 py-1 rounded w-fit"
                                        :class="file.type === 'PDF' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'">
                                        {{ file.type }}
                                    </div>
                                </td>
                                <td class="p-4 font-medium text-gray-900 max-w-xs truncate">
                                    {{ file.original_name }}
                                    <div v-if="file.latest_print_request" class="mt-1">
                                        <span
                                            class="text-[10px] bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded font-bold">
                                            REQ: {{ file.latest_print_request.request_id }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-gray-500">
                                    {{ new Date(file.created_at).toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) }}
                                </td>
                                <td class="p-4 text-center">
                                    <span v-if="!file.latest_print_request"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                        BELUM REQ
                                    </span>
                                    <span v-else-if="file.status === 'pending'"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                        PENDING
                                    </span>
                                    <span v-else-if="file.status === 'verified'"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        VERIFIED
                                    </span>
                                    <span v-else-if="file.status === 'rejected'"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        REJECTED
                                    </span>
                                    <span v-else-if="file.status === 'completed'"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        COMPLETED
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openPrintModal(file)" class="p-2 rounded-lg transition-colors"
                                            :class="{
                                                'bg-indigo-50 hover:bg-indigo-100 text-indigo-600': !file.status || ['new', 'rejected'].includes(file.status),
                                                'bg-yellow-50 hover:bg-yellow-100 text-yellow-600': file.status === 'pending',
                                                'bg-green-50 hover:bg-green-100 text-green-600': file.status === 'verified',
                                                'bg-blue-50 hover:bg-blue-100 text-blue-600': file.status === 'completed',
                                            }" title="Print Options">
                                            <Printer class="w-5 h-5" />
                                        </button>

                                        <button @click="openDeleteModal(file)"
                                            class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                            title="Hapus File">
                                            <Trash2 class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PRINT MODAL -->
        <div v-if="modalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            @click.self="closePrintModal">
            <div
                class="bg-white rounded-2xl w-full max-w-6xl h-[85vh] flex shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">

                <!-- PREVIEW -->
                <div class="w-2/3 bg-gray-200 relative flex items-center justify-center border-r border-gray-300">
                    <iframe :src="currentFile.url + '#toolbar=0&navpanes=0&scrollbar=0&view=FitH'"
                        class="w-full h-full bg-white"></iframe>
                </div>

                <!-- CONFIG PANEL -->
                <div class="w-1/3 bg-gray-50 flex flex-col h-full border-l border-white">
                    <div class="p-6 border-b border-gray-200 bg-white">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide">Konfigurasi Cetak</h2>
                        <p class="text-xs text-gray-500 font-bold mt-1">Sesuaikan kebutuhan cetak Anda</p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        <!-- File Info -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <div class="font-bold text-gray-900 truncate">{{ currentFile.original_name }}</div>
                            <div class="text-xs text-gray-500 font-bold mt-1 flex items-center gap-2">
                                <span class="uppercase">{{ currentFile.type }}</span> • {{ config.pages }} Halaman
                            </div>
                        </div>

                        <!-- Configs -->
                        <!-- Configs (ONLY SHOW FOR NEW/REJECTED) -->
                        <div v-if="!currentFile.status || ['new', 'rejected', 'new_upload'].includes(currentFile.status)"
                            class="space-y-4">
                            <!-- Paper Size -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ukuran
                                    Kertas</label>
                                <select v-model="config.paperSize"
                                    class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-bold">
                                    <option value="A4">A4</option>
                                    <option value="Legal">Legal / F4</option>
                                </select>
                            </div>

                            <!-- Copies -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Copy</label>
                                <div class="flex items-center">
                                    <button @click="config.copies = Math.max(1, config.copies - 1)"
                                        class="w-10 h-10 bg-gray-200 rounded-l-xl font-bold hover:bg-gray-300">-</button>
                                    <input type="number" v-model="config.copies"
                                        class="w-full text-center border-y border-x-0 border-gray-200 h-10 font-bold text-lg"
                                        readonly>
                                    <button @click="config.copies++"
                                        class="w-10 h-10 bg-gray-200 rounded-r-xl font-bold hover:bg-gray-300">+</button>
                                </div>
                            </div>

                            <!-- Color Mode -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mode Warna</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer group">
                                        <input type="radio" value="color" v-model="config.colorMode"
                                            class="peer sr-only">
                                        <div
                                            class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-center transition-all group-hover:bg-gray-50 peer-disabled:opacity-50">
                                            <span class="font-bold text-sm text-gray-700">Berwarna</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer group">
                                        <input type="radio" value="bw" v-model="config.colorMode" class="peer sr-only">
                                        <div
                                            class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-center transition-all group-hover:bg-gray-50 peer-disabled:opacity-50">
                                            <span class="font-bold text-sm text-gray-700">Hitam Putih</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Page Range -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Halaman</label>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <label class="cursor-pointer group">
                                        <input type="radio" value="all" v-model="config.pageOption"
                                            class="peer sr-only">
                                        <div
                                            class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-center transition-all group-hover:bg-gray-50 peer-disabled:opacity-50">
                                            <span class="font-bold text-sm text-gray-700">Semua</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer group">
                                        <input type="radio" value="custom" v-model="config.pageOption"
                                            class="peer sr-only">
                                        <div
                                            class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 text-center transition-all group-hover:bg-gray-50 peer-disabled:opacity-50">
                                            <span class="font-bold text-sm text-gray-700">Custom</span>
                                        </div>
                                    </label>
                                </div>
                                <div v-if="config.pageOption === 'custom'"
                                    class="animate-in slide-in-from-top-2 duration-200">
                                    <input type="text" v-model="config.customPages" placeholder="Contoh: 1,3,5-10"
                                        class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-bold">
                                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Pisahkan dengan koma (,) atau
                                        gunakan tanda hubung (-) untuk rentang.</p>
                                </div>
                            </div>
                        </div>

                        <!-- PENDING / VERIFIED INFO (REPLACES CONFIG WHEN NOT NEW) -->
                        <div v-else class="space-y-6">
                            <!-- Show summary of selected config -->
                            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                <p class="text-[10px] text-indigo-400 font-bold uppercase mb-2">Konfigurasi Terpilih</p>
                                <div class="grid grid-cols-2 gap-y-3 gap-x-2">
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase font-bold">Kertas</p>
                                        <p class="text-xs font-black text-gray-700">{{ config.paperSize }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase font-bold">Warna</p>
                                        <p class="text-xs font-black text-gray-700 uppercase">{{ config.colorMode ===
                                            'color' ? 'Berwarna' : 'H/P' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase font-bold">Copy</p>
                                        <p class="text-xs font-black text-gray-700">{{ config.copies }}x</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase font-bold">Hal</p>
                                        <p class="text-xs font-black text-gray-700">{{ config.pageOption === 'all' ?
                                            'Semua' : config.customPages }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Status UI (Inside Content) -->
                            <div v-if="currentFile.status === 'pending'" class="text-center py-4">
                                <div
                                    class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                                    <Clock class="w-10 h-10" />
                                </div>
                                <h3 class="text-xl font-black text-yellow-700 uppercase">MENUNGGU KONFIRMASI</h3>
                                <p class="text-sm text-yellow-500 font-bold mt-2">Daftar verifikasi Anda sedang diproses
                                    oleh admin.</p>
                            </div>

                            <!-- Verified Status UI (Inside Content) -->
                            <div v-if="currentFile.status === 'verified'" class="text-center py-4">
                                <div
                                    class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <CheckCircle class="w-10 h-10" />
                                </div>
                                <h3 class="text-xl font-black text-green-700 uppercase">SIAP DICETAK</h3>
                                <p class="text-sm text-green-500 font-bold mt-2">Permintaan telah disetujui. Silakan
                                    tekan tombol eksekusi.</p>
                            </div>

                            <!-- Completed Status UI (Inside Content) -->
                            <div v-if="currentFile.status === 'completed'" class="text-center py-4">
                                <div
                                    class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <Printer class="w-10 h-10" />
                                </div>
                                <h3 class="text-xl font-black text-blue-700 uppercase">SELESAI DICETAK</h3>
                                <p class="text-sm text-blue-500 font-bold mt-2">File ini sudah berhasil dicetak.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white border-t border-gray-200 space-y-3">
                        <!-- STATUS NEW / REJECTED -->
                        <div v-if="!currentFile.status || ['new', 'rejected', 'new_upload'].includes(currentFile.status)"
                            class="space-y-3">
                            <button @click="submitRequest"
                                class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-lg shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                                <span>SUBMIT REQUEST</span>
                                <CheckCircle class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- STATUS PENDING -->
                        <div v-else-if="currentFile.status === 'pending'" class="space-y-4">
                            <div class="bg-white p-4 rounded-xl border-2 border-yellow-200 border-dashed text-center">
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Kode Request</p>
                                <p class="text-2xl font-black text-yellow-700 font-mono tracking-widest">{{
                                    currentFile.latest_print_request.request_id }}</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-yellow-400 text-white rounded-full flex items-center justify-center">
                                    <Clock class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-yellow-800 font-black text-sm uppercase">Pending</h3>
                                    <p class="text-yellow-600 text-[10px] font-bold">Menunggu persetujuan admin stasiun
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- STATUS VERIFIED -->
                        <div v-else-if="currentFile.status === 'verified'" class="space-y-4">
                            <div class="bg-white p-4 rounded-xl border-2 border-green-200 border-dashed text-center">
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Kode Request</p>
                                <p class="text-2xl font-black text-green-700 font-mono tracking-widest">{{
                                    currentFile.latest_print_request.request_id }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-xl border border-green-100 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center">
                                    <CheckCircle class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-green-800 font-black text-sm uppercase">Siap Print!</h3>
                                    <p class="text-green-600 text-[10px] font-bold">Verifikasi selesai, silakan cetak
                                    </p>
                                </div>
                            </div>
                            <button @click="executePrint" :disabled="loading"
                                class="w-full py-4 bg-green-600 hover:bg-green-700 text-white rounded-xl font-black text-lg shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="loading">MENCETAK...</span>
                                <span v-else>EKSEKUSI PRINT</span>
                                <Printer v-if="!loading" class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- STATUS COMPLETED -->
                        <div v-else-if="currentFile.status === 'completed'" class="space-y-4">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                    <CheckCircle class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-blue-800 font-black text-sm uppercase">Cetak Selesai</h3>
                                    <p class="text-blue-600 text-[10px] font-bold">Terima kasih telah mencetak!</p>
                                </div>
                            </div>
                        </div>

                        <button @click="closePrintModal"
                            class="w-full py-3 text-gray-500 hover:text-gray-800 font-bold">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div v-if="deleteModalOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl p-8 w-96 text-center shadow-xl">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus File?</h3>
                <p class="text-gray-500 mb-6">File ini akan dihapus permanen dari server.</p>
                <div class="flex gap-3">
                    <button @click="deleteModalOpen = false"
                        class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-gray-700">
                        Batal
                    </button>
                    <button @click="confirmDelete"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-700 rounded-xl font-bold text-white shadow-lg shadow-red-200">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- BULK DELETE MODAL -->
        <div v-if="bulkDeleteModalOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl p-8 w-96 text-center shadow-xl">
                <div
                    class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <Trash2 class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus {{ selectedIds.length }} File?</h3>
                <p class="text-gray-500 mb-6">Semua file yang dipilih akan dihapus permanen dari server.</p>
                <div class="flex gap-3">
                    <button @click="bulkDeleteModalOpen = false"
                        class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-gray-700">
                        Batal
                    </button>
                    <button @click="confirmBulkDelete"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-700 rounded-xl font-bold text-white shadow-lg shadow-red-200">
                        Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>