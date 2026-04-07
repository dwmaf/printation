<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    station: Object
});

const form = useForm({
    station_id: props.station.id,
    file: []
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => Object.values(page.props.errors)[0]);

const handleFileChange = (e) => {
    form.file = Array.from(e.target.files);
    if (form.file.length > 0) {
        uploadFiles();
    }
};

const uploadFiles = () => {
    form.post(route('upload.store', props.station.id), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Upload File" />
    <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 font-roboto">
        <label for="input-file" class="block w-full max-w-lg p-7 bg-white text-center rounded-3xl shadow-sm border border-gray-100 cursor-pointer">
            <div class="flex justify-center items-center mb-6 gap-3">
                <img src="/images/logo.png" class="w-16 object-contain" />
                <h1 class="text-4xl font-koulen">PRINTATION</h1>
            </div>

            <h2 class="uppercase font-bold text-gray-300 mb-6 tracking-widest text-sm">{{ station.name }}</h2>

            <input type="file" id="input-file" @change="handleFileChange" multiple hidden />

            <!-- UPLOAD AREA -->
            <div 
                :class="[
                    'w-full aspect-square max-h-75 border-4 border-dashed rounded-[40px] flex flex-col justify-center items-center p-8 mb-6 transition-all',
                    form.processing ? 'border-gray-200 bg-gray-50 opacity-50' : 'border-[#6155F5] bg-[#F7F8FF] hover:bg-[#F0F1FF]'
                ]"
            >
                <img src="/images/upload.png" class="w-32 mb-4" v-if="!form.processing" />
                <div v-else class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                
                <p class="text-xl font-bold text-gray-800">
                    {{ form.processing ? 'Sedang Mengunggah...' : 'Klik disini untuk mengunggah file.' }}
                </p>
                <span class="block text-xs mt-3 text-gray-400 leading-relaxed font-medium">
                    Format PDF, PNG, JPG, dan JPEG <br>
                    max. 10MB per file
                </span>
            </div>

            <!-- STATUS MESSAGES -->
            <div v-if="successMessage" class="space-y-1">
                <p class="text-green-600 font-bold text-lg">✅ {{ successMessage }}</p>
                <p class="text-sm text-gray-500">Silakan cek kembali komputer anjungan.</p>
            </div>

            <div v-if="errorMessage" class="space-y-1">
                <p class="text-red-600 font-bold text-lg">❌ Gagal!</p>
                <p class="text-sm text-gray-500">{{ errorMessage }}</p>
            </div>
        </label>
    </div>
</template>
