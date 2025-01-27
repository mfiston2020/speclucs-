<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\SoldProduct;
use App\Repositories\StockTrackRepo;
use Livewire\Component;

class SoldLensReport extends Component
{
    public $result = false;
    public $start_date, $end_date, $searchFoundSomething = '', $soldLens = [], $lens, $results   =   [];

    public $lens_type, $index, $chromatics, $coatings;
    public $len_type, $indx, $chromatic, $coating;

    function searchSoldLens()
    {
        $this->results  =   [];
        $this->validate([
            'end_date'  => 'required|date|after:start_date',
            'start_date' => 'required|date',
            'len_type' => 'required',
            'indx'     => 'required',
            'chromatic' => 'required',
            'coating'   => 'required',
        ]);

        $repo   =   new StockTrackRepo();

        $this->results   =   $repo->stockLens($this->len_type, $this->indx, $this->chromatic, $this->coating);

        $this->soldLens =   SoldProduct::where('company_id',auth()->user()->company_id)->wherebetween('created_at', [$this->start_date, $this->end_date])->select('id','product_id','quantity')->get();

        if (count($this->results) > 0) {
            $this->searchFoundSomething =   'yes';
        } else {
            $this->searchFoundSomething =   'no';
        }
    }

    function mount()
    {
        $this->lens_type    =   \App\Models\LensType::all();
        $this->index        =   \App\Models\PhotoIndex::all();
        $this->coatings     =   \App\Models\PhotoCoating::all();
        $this->chromatics   =   \App\Models\PhotoChromatics::all();
    }

    public function render()
    {
        return view('livewire.manager.report.sold-lens-report')->layout('livewire.livewire-slot');
    }
}
