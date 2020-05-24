<?php
  namespace G3n1us\Editor;
  
  use Illuminate\Http\Request;
  use Illuminate\Foundation\Bus\DispatchesJobs;  
  use Illuminate\Routing\Controller as BaseController;
  use Illuminate\Foundation\Validation\ValidatesRequests;
  use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
  
  use G3n1us\Editor\Models\File; 
  use G3n1us\Editor\Models\Page; 
  
  class EditorController extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function getFilemanager(Request $request){
  		return view('g3n1us_editor::filemanager');
    }
    
    public function storeUpload(Request $request){
  		if(!$request->hasFile('upload'))
  			abort(500);

  		$requestfiles = is_array($request->file('upload')) ? $request->file('upload') : [$request->file('upload')];
  		$responsefiles = [];
  		foreach($requestfiles as $requestfile){
    	    if ($requestfile->isValid()){
      			$originalname = $requestfile->getClientOriginalName();
      			$md5 = md5_file($requestfile->path());
      			$path = $requestfile->storeAs($md5, $originalname, config('g3n1us_editor.disk'));
      	    $newfile = new File;      			
      	    $newfile->filename = $originalname;
      	    $newfile->path = $path;
      	    $newfile->mime_type = $requestfile->getMimeType();
      	    $newfile->extension = $requestfile->extension();
      	    $newfile->save();
      	    $responsefiles[] = $newfile;
      			
      		} // isvalid  
      }
      
			cache()->forget('cached_files');
			if($request->redirect)
  			return redirect()->back();
			else
  			return [
  				'uploaded' => 1,
  				'filename' => $responsefiles[0]->path,
  				'url' => \Storage::url($responsefiles[0]->path)
  			];
    }
    
    public function deleteFile(Request $request){
      return $request;
    }
    
    public function routePage(Request $request, $page = '/'){
      $page = Page::where('path', $page)->firstOrFail();

      return response($page->display());
    }
  }
  