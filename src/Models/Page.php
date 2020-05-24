<?php
  
namespace G3n1us\Editor\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use G3n1us\Editor\Traits\Displayable;

class Page extends Model{
    use Displayable;
    use Searchable;

    protected $table = 'editor_pages';
    
    protected $casts = [
        'metadata' => 'object',
        'metadata->hidden_from_nav' => 'boolean',
        'metadata->show_in_nav'     => 'boolean',
    ];
    
    protected static function booted(){
        static::saving(function ($page) {
	        if($page->path != '/'){
		        $page->path = trim($page->path, '/');
	        }
        });
    }
    
        
    public function getPathAttribute($path){
      return str_start($path, '/');
    }
    
    public function versions(){
      return $this->hasMany(PageContent::class);
    }
    
    public function page_content(){
      return $this->belongsTo(PageContent::class);
    }
    
    /**
     * Get all of the tags for the post.
     */
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    
    public function getContentAttribute(){
	    return $this->page_content ? $this->page_content->html : '';
    }
    
    
    public function setContentAttribute($val){
	    if(is_numeric($val)){
		    $version_id = $val;
	    }
	    else{
		    $version = $this->versions()->create(['html' => $val]);
		    $version_id = $version->id;
	    }
	    

	    $this->page_content_id = $version_id;
	    $this->save();
// 	    return $this->content->html;
    }
    
    
    public function getHtmlAttribute($value){
      $page_content = $this->page_content;
      return $page_content ? $page_content->html : $value;
    }
    
    public function getShowInNavAttribute(){
	    return @$this->metadata->show_in_nav;
    }

    public function getTemplateAttribute(){
	    return @$this->metadata->template;
    }

    public function getRedirectAttribute(){
	    return @$this->metadata->redirect;
    }

}
  
