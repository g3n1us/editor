<?php
  
namespace G3n1us\Editor;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

use App\User;

class PageContent extends Model{
    use Traits\Displayable;
    use Searchable;    
  
    protected $table = 'editor_page_contents';
    
    protected $fillable = ['page_id'];
    
    public function user(){
      return $this->belongsTo(User::class);
    }
    
    public function page(){
      return $this->belongsTo(Page::class);
    }

}
  
