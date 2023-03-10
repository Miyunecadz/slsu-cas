<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Feedback extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.feedback',[
            'ratings' => \App\Models\Rating::inRandomOrder()->limit(5)->get()
        ]);

    }
}
