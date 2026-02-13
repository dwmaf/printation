@extends('layouts.app-admin-dashboard')

@section('child')
<div class="h-full">
  <div class="grid grid-cols-6 grid-rows-6 gap-4 h-full">
    {{-- Pemasukan bulan ini --}}
    <div class="col-span-2 row-span-2 shadow-md rounded-lg p-6 flex flex-col gap-2">
        <div class="flex justify-between">
          <div>
            <p class="font-bold text-xl">Pemasukan Bulan Ini</p>
            <p class="font-medium text-sm text-[#C2C2C2]">{{ now()->translatedFormat('F Y') }}</p>
          </div>
          <div class="p-2 bg-[#60FC99] rounded-3xl">
            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24">
              <path fill="#0D7432" fill-rule="evenodd"
                  d="M20.41 9.86a3 3 0 0 0-.175-.003H17.8c-1.992 0-3.698 1.581-3.698 3.643s1.706 3.643 3.699 3.643h2.433q.092.001.175-.004a1.7 1.7 0 0 0 1.586-1.581c.004-.059.004-.122.004-.18v-3.756c0-.058 0-.121-.004-.18a1.7 1.7 0 0 0-1.585-1.581m-2.823 4.611c.513 0 .93-.434.93-.971s-.417-.971-.93-.971s-.929.434-.929.971s.416.971.93.971"
                  clip-rule="evenodd" />
              <path fill="#0D7432" fill-rule="evenodd"
                  d="M20.234 18.6a.214.214 0 0 1 .214.27c-.194.692-.501 1.282-.994 1.778c-.721.727-1.636 1.05-2.766 1.203c-1.098.149-2.5.149-4.272.149h-2.037c-1.771 0-3.174 0-4.272-.149c-1.13-.153-2.045-.476-2.766-1.203C2.62 19.923 2.3 19 2.148 17.862C2 16.754 2 15.34 2 13.555v-.11c0-1.785 0-3.2.148-4.306C2.3 8 2.62 7.08 3.34 6.351c.721-.726 1.636-1.05 2.766-1.202C7.205 5 8.608 5 10.379 5h2.037c1.771 0 3.174 0 4.272.149c1.13.153 2.045.476 2.766 1.202c.493.497.8 1.087.994 1.78a.214.214 0 0 1-.214.269h-2.433c-2.734 0-5.143 2.177-5.143 5.1s2.41 5.1 5.144 5.1zM5.614 8.886a.725.725 0 0 0-.722.728c0 .403.323.729.722.729H9.47c.4 0 .723-.326.723-.729a.726.726 0 0 0-.723-.728z"
                  clip-rule="evenodd" />
              <path fill="#0D7432"
                  d="m7.777 4.024l1.958-1.443a2.97 2.97 0 0 1 3.53 0l1.969 1.451C14.41 4 13.49 4 12.483 4h-2.17c-.922 0-1.769 0-2.536.024" />
            </svg>
          </div>
        </div>
        <p class="text-4xl font-bold mb-1">Rp 10.000.000</p>
        <div class="flex gap-2">
          <img src=" {{ asset('images/trend-up.svg') }} " alt="">
          <span class="text-[#05E7C6] font-bold">12%</span> dari bulan lalu
        </div>
    </div>
    <div class="col-span-2 row-span-2 col-start-3 shadow-md rounded-lg p-6 flex flex-col gap-2">
      <div class="flex justify-between">
          <div>
            <p class="font-bold text-xl">Outlet Terdaftar Bulan Ini</p>
            <p class="font-medium text-sm text-[#C2C2C2]">{{ now()->translatedFormat('F Y') }}</p>
          </div>
          <div class="p-2 bg-amber-500 rounded-3xl">
            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z"/><path fill="#bb4d00" fill-rule="nonzero" d="M11 13v2H9v-2z"/><path fill="#bb4d00" d="M6.48 4a2 2 0 0 0-1.561.75l-3.05 3.813C1.083 9.545 1.783 11 3.04 11H4v7a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-7h2v8a1 1 0 1 0 2 0v-8h.96c1.258 0 1.957-1.455 1.171-2.437l-3.05-3.812A2 2 0 0 0 17.52 4zM8 11a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z"/></g></svg>
          </div>
        </div>
        <p class="text-4xl font-bold mb-1">10.000</p>
        <div class="flex gap-2">
          <img src=" {{ asset('images/trend-down.svg') }} " alt="">
          <span class="text-[#F93C65] font-bold">12%</span> dari bulan lalu
        </div>
    </div>
    <div class="col-span-2 row-span-2 col-start-5 shadow-md rounded-lg">3</div>
    <div class="col-span-3 row-span-4 row-start-3 shadow-md rounded-lg">
      
    </div>
    <div class="col-span-3 row-span-4 col-start-4 row-start-3 shadow-md rounded-lg">5</div>
  </div>
</div>
@endsection