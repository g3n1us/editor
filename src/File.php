<?php
  
namespace G3n1us\Editor;

use Illuminate\Database\Eloquent\Model;

class File extends Model{
  protected $table = 'editor_files';
  protected $appends = [
    'url',
    'last_modified_string'
  ];
  
  public function getUrlAttribute(){
    return url(\Storage::url($this->path));
  }
  
  public function getLastModifiedStringAttribute(){
    return $this->updated_at ? $this->updated_at->toDateTimeString() : null;
  }
}

/*

    				$fileobj['mime'] = mime($file);
    				$fileobj['icon'] = file_icon($file);
    				$fileobj['is_image'] = is_image($file);
    				$fileobj['filename'] = basename($file);
    				$fileobj['url'] = url(Storage::disk('public')->url($file));
    				$fileobj['path'] = Storage::disk('public')->url($file);
    				$fileobj['delete_path'] = $file;
    				$fileobj['last_modified'] = Storage::disk('public')->lastModified($file);
    				$fileobj['last_modified_string'] = \Carbon\Carbon::createFromTimestamp($fileobj['last_modified'])->toDateTimeString();
*/
