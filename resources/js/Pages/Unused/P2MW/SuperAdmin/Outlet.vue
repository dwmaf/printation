<script setup>
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Store, UserPlus, ShieldCheck, Mail, MapPin, Laptop, Edit, X } from 'lucide-vue-next';

const props = defineProps({
    outlets: Object
});

const isEditModalOpen = ref(false);

const form = useForm({
    outlet_name: '',
    address: '',
    max_stations: 1,
    owner_name: '',
    owner_email: '',
    owner_password: '',
});

const editForm = useForm({
    id: null,
    name: '',
    max_stations: 1,
});

const submit = () => {
    form.post(route('admin.upa.outlets.store'), {
        onSuccess: () => form.reset(),
    });
};

const openEditModal = (outlet) => {
    editForm.id = outlet.id;
    editForm.name = outlet.name;
    editForm.max_stations = outlet.max_stations;
    isEditModalOpen.value = true;
};

const updateOutlet = () => {
    editForm.put(route('admin.upa.outlets.update', editForm.id), {
        onSuccess: () => isEditModalOpen.value = false,
    });
};
</script>

<template>
    <Head title="Manage Outlets" />
    <AdminLayout>
        <div class="flex flex-col lg:flex-row gap-6 p-4">
            <!-- TABEL DAFTAR OUTLET -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <Store class="text-indigo-600" /> Daftar Mitra Outlet
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="p-4 font-semibold">Outlet Info</th>
                                    <th class="p-4 font-semibold">Owner</th>
                                    <th class="p-4 font-semibold">Quota</th>
                                    <th class="p-4 font-semibold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="outlet in outlets.data" :key="outlet.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800">{{ outlet.name }}</p>
                                        <div class="flex items-center text-xs text-gray-400 gap-1 mt-1">
                                            <MapPin size="12" /> {{ outlet.address }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm">
                                        <div v-if="outlet.owner">
                                            <p class="font-medium text-gray-700">{{ outlet.owner.name }}</p>
                                            <p class="text-xs text-gray-400">{{ outlet.owner.email }}</p>
                                        </div>
                                        <span v-else class="text-red-400 text-xs">Unassigned</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded text-xs font-bold flex items-center w-fit gap-1">
                                            <Laptop size="12" /> {{ outlet.max_stations }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button @click="openEditModal(outlet)" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors">
                                            <Edit size="18" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-auto p-4 border-t border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <p class="text-xs text-gray-400">Total: {{ outlets.total }} mitra</p>
                        <div class="flex gap-1">
                            <Link v-for="link in outlets.links" :key="link.label" :href="link.url || '#'" v-html="link.label"
                                class="px-3 py-1 text-xs rounded transition-all"
                                :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-500 hover:bg-gray-50'" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM REGISTRASI -->
            <div class="w-full lg:w-80 shrink-0">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <UserPlus class="text-indigo-600" /> Registrasi Mitra
                    </h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-3">
                            <input v-model="form.outlet_name" type="text" placeholder="Nama Outlet" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" required />
                            <input v-model="form.address" type="text" placeholder="Alamat" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" />
                            <input v-model="form.max_stations" type="number" placeholder="Jatah PC" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" required min="1" />
                        </div>
                        <div class="pt-4 border-t border-gray-50">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-3">Data Owner</p>
                            <div class="space-y-3">
                                <input v-model="form.owner_name" type="text" placeholder="Nama Lengkap" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" required />
                                <input v-model="form.owner_email" type="email" placeholder="Email Login" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" required />
                                <input v-model="form.owner_password" type="password" placeholder="Password" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" required />
                            </div>
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Mitra' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div v-if="isEditModalOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Detail Outlet</h3>
                    <button @click="isEditModalOpen = false"><X class="text-gray-400" /></button>
                </div>
                <form @submit.prevent="updateOutlet" class="space-y-4">
                    <input v-model="editForm.name" type="text" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" placeholder="Nama Outlet" />
                    <input v-model="editForm.max_stations" type="number" class="w-full border-gray-200 border rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" placeholder="Max Station" />
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="isEditModalOpen = false" class="flex-1 py-3 text-gray-500 font-bold">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>