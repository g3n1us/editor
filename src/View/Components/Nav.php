<?php

namespace G3n1us\Editor\View\Components;

use Illuminate\View\Component;

class Nav extends Component
{
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page = null){
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render(){
return <<<'blade'
		<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
			<div class="navbar-nav">
				{{ $slot }}
				{!! navbar() !!}
			</div>
		</nav>
blade;
	    
    }
}




