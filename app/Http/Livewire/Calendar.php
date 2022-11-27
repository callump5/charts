<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Submission;
use Carbon\Carbon;   
use Illuminate\Support\Facades\DB;

class Calendar extends Component
{
    public array $dayNames = [
        "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"
    ];

    public string $date;

    public string $currentYear;    
    public string $currentMonth;
    public string $currentDay;

    public string $firstDayOfMonth;
    public int $firstDayOfMonthInt;

    public string $lastDayOfMonth;
    public int $lastDayOfMonthInt;


    public $monthlySubmissions;
    public $weeklySubmissions;


    public int $index = 1;
    public int $rowCount = 1;   

    
    
    public function mount()
    {
        // $this->date = date("Y-m-d", strtotime("2022-12-06"));
        $this->date = date("Y-m-d");
    }
    

    public function nextMonth()
    {    
        $this->date  = date("Y-m-d", strtotime($this->date. " + 1 months"));
        
    }
    
    public function previousMonth()
    {
        $this->date  = date("Y-m-d", strtotime($this->date. " - 1 months"));

    }

    public function createSubmission()
    {
        $submission = new Submission();
        // $submission->created_at = "2022-12-04 23:04:54";
        $submission->save();

        $this->updateSubmission();
    }

    
    public function updateSubmission()
    {
        $this->carbonDate = Carbon::create($this->date);

        $monday = date("Y-m-d", strtotime("monday this week", strtotime($this->date)));   
        $sunday = date("Y-m-d", strtotime("sunday this week", strtotime($this->date)));   

        $this->monthlySubmissions = DB::table("submissions")
        ->whereMonth('created_at', $this->carbonDate)
        ->select(DB::raw("DATE(created_at) as date"), DB::raw("count(*) as views"))
        ->groupBy("date")
        ->get();


        $this->weeklySubmissions = DB::table("submissions")
            ->whereBetween('created_at', [$monday, $sunday])
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("count(*) as views"))
            ->groupBy("date")
            ->get();


        $this->dispatchBrowserEvent("submissionUpdate", [
            "monthlySubmissions" => $this->monthlySubmissions,
            "weeklySubmissions" => $this->weeklySubmissions
        ]);


    }
    public function render()
    {
        $this->daysOfMonth = date("t", strtotime($this->date )) + 1 ;

        $monthInt =  date("m", strtotime($this->date ));
        $yearInt =  date("Y", strtotime($this->date ));
        $firstDay = mktime (0, 0, 0, $monthInt, 1, $yearInt);  
        $lastDay = mktime (0, 0, 0, $monthInt,  $this->daysOfMonth - 1, $yearInt);  
            

        $this->currentMonth = date("F", strtotime($this->date ));
        $this->currentYear = date("Y", strtotime($this->date ));
        $this->currentDay = date("d", strtotime($this->date ));
           
        $this->firstDayOfMonth = date("D", $firstDay);
        $this->firstDayOfMonthInt = date("d", $firstDay);

        $this->lastDayOfMonth = date("D", $lastDay);
        $this->lastDayOfMonthInt = date("d", $lastDay);


        $this->carbonDate = Carbon::create($this->date);

        $this->updateSubmission();


        return view("livewire.calendar");
    }
}
