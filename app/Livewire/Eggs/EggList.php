<?php

namespace App\Livewire\Eggs;

use App\Models\EggProduction;
use Livewire\Component;

class EggList extends Component
{
    public function render()
    {
        $eggs = EggProduction::all();
        return view(
            'livewire.eggs.egg-list',
            ['eggs' => $eggs]
        );
    }
}
