<div class="flex items-center justify-center p-8 bg-green-400 min-h-[100vh] flex-wrap">


    <div class="w-full mb-1">
        <x-card>
            @dump($currentMonth, $currentYear, $currentDay, $monthlySubmissions, $this->carbonDate->month, $dayNames, $firstDayOfMonth)
        </x-card>
    </div>

    <div class="flex flex-row w-full gap-10">

        <!-- begin::sideView::Calendar -->
        <div class="col w-3/12 ">
            <x-card>
                <div class="dark:bg-gray-800 bg-white  min-h-[330px]">
                    <!-- Calendar Header -->
                    <div class="flex items-center justify-between">
                        <span tabindex="0"
                            class="focus:outline-none  text-base font-bold dark:text-gray-100 text-gray-800">{{$currentMonth}} {{$currentYear}}</span>
                        <div class="flex items-center">
                            <button aria-label="calendar backward" wire:click="previousMonth"
                                class="focus:text-gray-400 hover:text-gray-400 text-gray-800 dark:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="15 6 9 12 15 18" />
                                </svg>
                            </button>
                            <button aria-label="calendar forward" wire:click="nextMonth"
                                class="focus:text-gray-400 hover:text-gray-400 ml-3 text-gray-800 dark:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler  icon-tabler-chevron-right"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="9 6 15 12 9 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Calendar Content --> 
                    <div class="flex items-center justify-between pt-6 overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>

                                    @foreach ($dayNames as $days)
                                        <th>
                                            <div class="w-full flex justify-center">
                                                <p class="text-base font-medium text-center text-gray-800 dark:text-gray-100">{{$days}}</p>
                                            </div>
                                        </th>
                                    @endforeach
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @while($index !== $daysOfMonth)
                                    @if($index === 1)
                                        @foreach ($dayNames as $day )
                                            @if($day === $firstDayOfMonth)
                                                <x-calendar-item :day="$index" :currentDay="$currentDay"></x-calendar-item>
                                                <?php $index++ ?>
                                                @break
                                            @else
                                                <x-calendar-item day="" :currentDay="$currentDay"></x-calendar-item>
                                            @endif
                                            
                                            <?php $rowCount++; ?>

                                        @endforeach
                                    @elseif($index !== 1)
                                        <x-calendar-item :day="$index" :currentDay="$currentDay"></x-calendar-item>

                                        <?php $rowCount++ ?>
                                        <?php $index++ ?>
                                    @endif
                                    
                                    @if ($rowCount % 7 === 0)
                                        </tr>
                                        <tr> 
                                    @endif

                                @endwhile
                            </tbody>
                        </table>
                    </div>

                    <button wire:click="createSubmission">Submit Item</button>
                </div>
            </x-card>
        </div>
        <!-- end::sideView::Calendar -->

        <!-- begin::mainContent::Cards -->
        <div class="col w-9/12 ">
            <x-card title="{{$currentMonth}} Overview">
                <canvas class="max-h-[300px]" id="monthlySubs"></canvas>
            </x-card>
            <x-card title="Weekly View">
                <canvas class="max-h-[300px]" id="weeklySubs"></canvas>
            </x-card>
            <x-card title="Daily View">
                <canvas class="max-h-[300px]" id="dailySubs"></canvas>
            </x-card>
        </div>
        <!-- begin::mainContent::Calendar -->
    </div>


    <script>


        function createCharts(event = null){

            let monthlySubsChart = document.getElementById('monthlySubs');
            let weeklySubsChart = document.getElementById('weeklySubs');


            var monthlyData = (event === null) ? @js($monthlySubmissions) : event.monthlySubmissions;
            var weeklyData = (event === null) ? @js($weeklySubmissions) : event.weeklySubmissions;

            console.log(monthlyData, weeklyData);

            var monthlyLabels = [];
            var monthlyValues = [];

            var weeklyLabels = [];
            var weeklyValues = [];


            monthlyData.forEach(function(e,i){
                monthlyLabels.push(e.date);
                monthlyValues.push(e.views);
            })


            weeklyData.forEach(function(e,i){
                weeklyLabels.push(e.date);
                weeklyValues.push(e.views);
            })

            if (monthlyData.length > 0) {
                new Chart(monthlySubsChart, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                        label: '# of Votes',
                        data: monthlyValues,
                        borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
                });
                
            }
            if (weeklyData.length > 0) {
                new Chart(weeklySubsChart, {
                    type: 'line',
                    data: {
                        labels: weeklyLabels,
                        datasets: [{
                        label: '# of Votes',
                        data: weeklyValues,
                        borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
                });
            }

        }


        createCharts();



        window.addEventListener('submissionUpdate', event => {
            jQuery('<canvas id="monthlySubs" class="max-h-[300px]"></canvas>').insertAfter('#monthlySubs');
            jQuery('<canvas id="weeklySubs" class="max-h-[300px]"></canvas>').insertAfter('#weeklySubs');
            jQuery('#monthlySubs')[0].remove()
            jQuery('#weeklySubs')[0].remove()
            
            createCharts(event.detail);
        })

      </script>
      
</div>