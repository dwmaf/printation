@extends("layouts.app")

@section("child")
<div>
    <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data"
        class="flex items-center justify-center min-h-screen">
        @csrf
        <input type="hidden" name="station_id" value="{{ $station->id }}">
        <label for="input-file" id="dropzone" class="block w-full max-w-lg p-7 bg-white text-center">
            <div class="flex justify-center items-center mb-6">
                <img src="{{ asset('images/logo.png') }}" class="w-16" />
                <h1 class="text-5xl font-koulen">PRINTATION</h1>
            </div>

            <h2 class="uppercase font-medium text-[#D4D4D4] mb-4 font-roboto">{{$station->name}}</h2>

            <input
                type="file"
                id="input-file"
                name="file[]"
                multiple
                hidden />

                @if (session()->has('success'))
                    <div id="upload-form" class="w-full h-full border-2 border-dashed border-[#6155F5] bg-[#F7F8FF] rounded-[20px] flex flex-col justify-center items-center p-6 mb-4">
                        <img src="{{ asset('images/upload.png') }}" class="w-32 mb-4" />
                        <p class="text-lg font-medium">
                            Klik disini untuk <br> mengunggah file.
                        </p>
                        <span class="block text-[12px] mt-3 text-[#777]">
                            Format PDF, PNG, JPG, dan JPEG <br>
                            max. 10MB hingga 10 file
                        </span>
                    </div>
                    <p class="text-green-600 font-semibold text-md">
                        ✅ {{ session('success') }}
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Please check your computer
                    </p>
                @else
                    <div id="upload-form" class="w-full h-full border-2 border-dashed border-[#6155F5] bg-[#F7F8FF] rounded-[20px] flex flex-col justify-center items-center p-6">
                        <img src="{{ asset('images/upload.png') }}" class="w-32 mb-4" />
                        <p class="text-lg font-medium">
                            Klik disini untuk <br> mengunggah file.
                        </p>
                        <span class="block text-[12px] mt-3 text-[#777]">
                            Format PDF, PNG, JPG, dan JPEG <br>
                            max. 10MB hingga 10 file
                        </span>
                    </div>
                @endif
        </label>

        @error('file')
            <small class="error">{{ $message }}</small>
        @enderror

        @error('file.*')
            <small class="error">{{ $message }}</small>
        @enderror
    </form>

    <script>
        const input = document.getElementById("input-file");
        input.addEventListener("change", function () {
            // paksa submit FORM (POST), bukan submit label
            this.closest("form").submit();
        });
    </script>
</div>
@endsection
