<?php
  
  namespace G3n1us\Editor;
  
  use Illuminate\Database\Eloquent\Relations\Relation;  
  use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
  use Illuminate\Support\Facades\Schema;
  use Illuminate\Database\Schema\Blueprint;
  
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
            'pages' => 'G3n1us\Editor\Page',
            'files' => 'G3n1us\Editor\File',
        ]);
      
      
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadRoutesFrom(base_path().'/routes/web.php'); // This is to prevent the wildcard route handler from gobbling all previously defined routes. TODO, investigate a better way to do this.
        $this->publishes([
            __DIR__.'/config.php' => config_path('g3n1us_editor.php'),
            __DIR__.'/assets' => public_path('vendor/g3n1us_editor'),            
        ]);
        $this->loadViewsFrom(__DIR__.'/views', 'g3n1us_editor');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        
    }    
    
  }
  
  
  
/*
        // TODO move this to a migration
        if(!Schema::hasTable('editor_files')){
          Schema::create('editor_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('path');
            $table->string('bucket')->nullable();
            $table->string('mime_type')->default('image/jpeg');
            $table->string('extension', 8)->default('jpg');
            $table->json('metadata')->nullable();
            $table->timestamps();
          });          
        }
        
        // TODO move this to a migration
        if(!Schema::hasTable('editor_pages')){
          Schema::create('editor_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_page_id')->nullable();
            $table->integer('page_content_id');
            $table->string('title');
            $table->string('path');
            $table->json('metadata')->nullable();
            $table->timestamps();
          });          
        }
        
        // TODO move this to a migration
        if(!Schema::hasTable('editor_page_contents')){
          Schema::create('editor_page_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id');
            $table->integer('user_id');
            $table->text('html');
            $table->timestamps();
          });          
        }
        
        // TODO move this to a migration
        if(!Schema::hasTable('tags')){
          Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
          });          
        }
        
        // TODO move this to a migration
        if(!Schema::hasTable('taggables')){
          Schema::create('taggables', function (Blueprint $table) {
            $table->integer('tag_id');
            $table->integer('taggable_id');
            $table->string('taggable_type');
            $table->timestamps();
          });          
        }
  
*/