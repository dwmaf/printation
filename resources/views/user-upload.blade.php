@extends("layouts.app")

{{-- bagian arif --}}
{{-- tampilan di hp user setelah scan kyur, tempat upload file, pakai form wire livewire, pastiin nama variabelnya sama dgn yg di UserUpload.php --}}

@section("child")
    <div>
        <form action="/upload" method="POST" enctype="multipart/form-data" class="flex items-center justify-center min-h-screen" id="uploadForm">
            @csrf
            <label for="input-file" id="dropzone" class="block w-full max-w-lg h-100 p-7 bg-white text-center cursor-pointer">
                <div class="flex gap-3 justify-center items-center mb-6">
                    <img src="{{ asset('images/placeholder_logo.png') }}" class="w-16" />
                    <h1 class="text-4xl font-bold">PRINT</h1>
                </div>
                <input
                    type="file"
                    id="input-file"
                    name="file[]"
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.docx"
                    hidden 
                    onchange="document.getElementById('uploadForm').submit()" />

                <div id="upload-form" class="w-full h-full border-2 border-dashed border-[#6155F5] bg-[#F7F8FF] rounded-[20px] flex flex-col justify-center items-center p-6">
                    @if (session()->has('success'))
                        <p class="text-green-600 font-semibold text-lg">
                            ✅ {{ session('success') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Please check your computer
                        </p>
                    @else
                        <img src="{{ asset('images/upload.png') }}" class="w-32 mb-4" />
                        <p class="text-lg font-medium">
                            Klik disini untuk <br> mengunggah file.
                        </p>
                        <span class="block text-[12px] mt-3 text-[#777]">Format PDF, PNG, JPG, dan JPEG <br> max. 10MB hingga 10 file</span>
                    @endif
                </div>
            </label>

            @error('file')
            <small class="error">{{ $message }}</small>
            @enderror
        </form>
        {{-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi --}}
    </div>
@endsection