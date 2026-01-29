{{-- bagian arif --}}
{{-- tampilan di hp user setelah scan kyur, tempat upload file, pakai form wire livewire, pastiin nama variabelnya sama dgn yg di UserUpload.php --}}
<div>
    <form wire:submit.prevent="save" class="flex items-center justify-center min-h-screen">
        <label for="input-file" id="dropzone" class=" block w-125 h-75 p-7 bg-white text-center">
            <input
                type="file"
                id="input-file"
                wire:model="file"
                wire:change="save"
                hidden />

            <div id="upload-form" class="w-full h-full border-2 border-dashed border-[#bbb5ff] bg-[#f7f8ff] rounded-[20px] flex flex-col justify-center items-center">
                @if (session()->has('uploaded'))
                    <p class="text-green-600 font-semibold text-lg">
                        ✅ File uploaded
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Please check your computer
                    </p>
                <!-- @elseif ($file)
                    <p class="text-blue-600 font-medium">
                        📤 File ready to upload
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        Tap anywhere to confirm
                    </p> -->
                @else
                    <img src="{{ asset('images/508-icon.png') }}" class="w-25 max-w-[40%] mt-6" />
                    <p>
                        Klik disini untuk <br> mengunggah file.
                    </p>
                    <span class="block text-[12px] mt-3 text-[#777]">Unggah file yang didukung.</span>
                @endif
            </div>
        </label>

        @error('file')
        <small class="error">{{ $message }}</small>
        @enderror

        <!-- <button type="submit">Upload</button> -->
    </form>
    {{-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi --}}
</div>