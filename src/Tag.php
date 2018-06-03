<?php

namespace G3n1us\Editor;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function pages()
    {
        return $this->morphedByMany(Page::class, 'taggable');
    }
    
    public function __toString(){
      return '<span class="badge badge-pill badge-primary" data-tag="'.$this->toJson().'">'.$this->name.'</span>';
    }
}
