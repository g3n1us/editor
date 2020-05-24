<?php
  
namespace G3n1us\Editor\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use G3n1us\Editor\Traits\Displayable;
use App\User;

class PageContent extends Model{
    use Displayable;
    use Searchable;    
  
    protected $table = 'editor_page_contents';
    
    protected $fillable = ['page_id', 'html'];
    
    
    protected static function booted(){
        static::saving(function ($page) {
			if(empty($page->user_id)){
				$page->user_id = auth()->user()->id;
			}

        });
    }
        
    
    public function user(){
      return $this->belongsTo(User::class);
    }
    
    public function page(){
      return $this->belongsTo(Page::class);
    }

}
  
