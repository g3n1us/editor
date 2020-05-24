<?php
  
  namespace G3n1us\Editor;
  
  use Illuminate\Support\Facades\Blade;
  
  use Illuminate\Database\Eloquent\Relations\Relation;  
  use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
  use Illuminate\Support\Facades\Schema;
  use Illuminate\Support\Facades\View;
  use Illuminate\Database\Schema\Blueprint;
  use G3n1us\Editor\Models\Alert;
  use Illuminate\Support\Facades\Gate;
  
  use G3n1us\Editor\View\Components\Toolbar;
  use G3n1us\Editor\View\Components\Nav;
  
  class ServiceProvider extends LaravelServiceProvider {
    
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'g3n1us_editor'
        );
        
    }    
    
    
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        
        Relation::morphMap([
            'pages' => 'G3n1us\Editor\Models\Page',
            'files' => 'G3n1us\Editor\Models\File',
        ]);
      
	    Gate::define('edit-page', function ($user) {
	        return !!$user;
	    });
      
		Blade::component('toolbar', Toolbar::class); 
		Blade::component('nav', Nav::class); 

		$this->loadRoutesFrom(__DIR__.'/routes.php');
        
        $this->loadRoutesFrom(__DIR__.'/console.php');
        //$this->loadRoutesFrom(base_path().'/routes/web.php'); // This is to prevent the wildcard route handler from gobbling all previously defined routes. TODO, investigate a better way to do this.
        $this->publishes([
            __DIR__.'/config.php' => config_path('g3n1us_editor.php'),
            __DIR__.'/assets' => public_path('vendor/g3n1us_editor'),            
        ]);
        $this->loadViewsFrom(__DIR__.'/views', 'g3n1us_editor');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
		        
        View::share('edit_mode', request()->has('edit_mode'));

    }    
    
  }
  
