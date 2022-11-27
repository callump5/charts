<td>
    @if($day === $currentDay)
    
        <div class="w-full h-full">
            <div
                class="flex items-center justify-center w-full rounded-full cursor-pointer">
                <a role="link" tabindex="0"
                    class="focus:outline-none  focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 focus:bg-indigo-500 hover:bg-indigo-500 text-base w-8 h-8 flex items-center justify-center font-medium text-white bg-indigo-700 rounded-full">{{$day}}</a>
            </div>
        </div>
    
    @else
        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">{{$day}}</p>
        </div>
    @endif
</td>