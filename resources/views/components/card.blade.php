<div class="w-full shadow-lg mb-2 ">
    <div class="md:p-8 p-5 dark:bg-gray-800 bg-white rounded-t">
        
        @isset($title)
            <div class="px-4 flex items-center justify-between">
                <h1 class="font-bold">{{ $title }}</h1>
            </div>
        @endisset
        {{-- <div class="flex items-center justify-between pt-12 overflow-x-auto"> --}}
            {{ $slot }}
        {{-- </div> --}}
    </div>
</div>