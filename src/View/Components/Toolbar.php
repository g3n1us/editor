<?php

namespace G3n1us\Editor\View\Components;

use Illuminate\View\Component;

class Toolbar extends Component
{
    
    public $page;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page = null){
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render(){
        return view('g3n1us_editor::toolbar');
    }
}
