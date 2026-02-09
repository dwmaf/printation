@extends('layouts.app')

@section('child')
<div class="min-h-screen bg-gray-900 flex items-center justify-center p-6">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-md border border-gray-700">
        <h2 class="text-3xl font-bold text-white text-center mb-2">Print App</h2>
        <p class="text-gray-400 text-center mb-8">Masuk ke sistem</p>

        @if($errors->any())
            <div class="bg-red-900/40 border border-red-500 text-red-200 px-4 py-3 rounded mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-400 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-gray-700 text-white rounded-lg p-3 border border-gray-600 focus:border-blue-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-gray-400 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full bg-gray-700 text-white rounded-lg p-3 border border-gray-600 focus:border-blue-500 focus:outline-none" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg transition-all shadow-lg shadow-blue-500/30">
                MASUK
            </button>
        </form>
    </div>
</div>
@endsection
