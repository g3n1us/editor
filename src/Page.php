<?php
  
namespace G3n1us\Editor;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Page extends Model{
    use Traits\Displayable;
    use Searchable;

    protected $table = 'editor_pages';
    
    protected $casts = [
        'metadata' => 'object',
        'metadata->hidden_from_nav' => 'boolean',
    ];
        
    public function getPathAttribute($path){
      return str_start($path, '/');
    }
    
    public function versions(){
      return $this->hasMany(PageContent::class);
    }
    
    public function page_content(){
      return $this->belongsTo(PageContent::class);
    }
    
    public function content(){
      return $this->hasOne(PageContent::class);
    }
    
    /**
     * Get all of the tags for the post.
     */
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    
    public function getHtmlAttribute($value){
      $page_content = $this->page_content;
      return $page_content ? $page_content->html : $value;
    }

}
  
