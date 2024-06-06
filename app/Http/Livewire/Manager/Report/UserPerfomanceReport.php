<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\TrackOrderRecord;
use App\Models\User;
use Livewire\Component;

class UserPerfomanceReport extends Component
{
    public  $searchFoundSomething   =   null;
    public $start_date, $end_date;
    public $company_users;

    public $user_perfomance_report = [];

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];

    // searching for users and then checking the perfomance 
    function searchInformation()
    {
        $this->searchFoundSomething =   null;
        $validated  =   $this->validate();

        $users  =   [];
        foreach ($this->company_users as $key => $value) {
            array_push($users, $value->id);
        }
        $this->user_perfomance_report   =   TrackOrderRecord::whereDate('created_at', '>=', date('Y-m-d', strtotime($validated['start_date'])))
            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($validated['end_date'])))
            ->whereIn('user_id', $users)->get();

        if (count($this->user_perfomance_report) <= 0) {
            $this->searchFoundSomething =   'no';
        } else {
            $this->searchFoundSomething =   'yes';
        }
    }


    function mount()
    {
        $this->company_users    =   User::where('company_id', auth()->user()->company_id)->select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.manager.report.user-perfomance-report')->layout('livewire.livewire-slot');
    }
}
