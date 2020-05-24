<?php

namespace G3n1us\Editor;

use Illuminate\Http\Request;
use View;
use G3n1us\Editor\Models\Page;
use G3n1us\Editor\Models\Alert;
use Storage;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class PageController extends BaseController
{
	
	private $page_templates;
	
    public function __construct()
    {
        $this->middleware('auth')->except('fallback');
        $this->middleware('can:edit-page')->except('fallback');
        
    }
    
    
/*
    public function fallback(Request $request, $path = 'index'){
	    
	    $dotpath = trim(implode(explode('/', "pages/$path"), '.'), '.');
	    
	    $page_search = (substr($dotpath, 6) == "index") ? "/" : substr($dotpath, 6);
	    
	    $page_search = str_replace('.', '/', $page_search);
	    
	    $page = Page::whereIn('path', [$page_search, "/$page_search", $path, "/$path"])->first();
	    
	    
	    
		if($page){	
			if($page->redirect) return redirect($page->redirect);
			
			$template = $page->template ?: 'default';
		    return view("pages.$template", ['page' => $page]);
	    }
	    else if(View::exists($dotpath))
		    return view($dotpath, ['page' => new Page]);
		    
	    else if($c = $this->find_from_archive($path)){
		    $fakepage = new Page;
		    $fakepage->content = $c['content'];
		    $fakepage->title = $c['title'];
		    return view('g3n1us_editor::pages.default', ['page' => $fakepage]);
	    }
	    else
		    abort(404);		    
	    
    }
*/
    
    
    
    public function index(){
	    return view('g3n1us_editor::dashboard.pages_list', ['pages' => Page::orderBy('sort_order')->get()]);
    }
    
    
    private function list_page_templates(){
	    if(!empty($this->page_templates)) return $this->page_templates;
	    
	    $this->page_templates = [];
	    $folders = [
		    __DIR__.'/views/pages',
		    base_path('resources/views/pages')
	    ];
	    foreach($folders as $folder){
		    if(!is_dir($folder)) continue;
		    $iterator = new \DirectoryIterator($folder);
		    foreach ($iterator as $fileInfo) {
			    if($fileInfo->isDot()) continue;
			    if(ends_with($fileInfo->getFilename(), 'blade.php') && !starts_with($fileInfo->getFilename(), '_') )
				    $this->page_templates[] = head(explode('.', basename($fileInfo->getFilename())));
		    }		    
	    }
	    return $this->page_templates;
    }
    
    public function edit(Request $request, Page $page){
	    $data['templates'] = $this->list_page_templates();
	    $data['page'] = $page;
	    
	    return view('g3n1us_editor::dashboard.pages_edit', $data);

    }
    
    
    
    public function create(Request $request){
	    return view('g3n1us_editor::dashboard.pages_create');

    }
    
    public function create_save(Request $request){
		$page = new Page;
		$page->title = $request->input('title');
		$page->path = !empty($request->path) ? $request->path : str_slug($page->title);
		$page->save();
		
		return redirect()->route('pages.edit', $page);
	}


    public function save(Request $request, Page $page){
	    $page->title = $request->input('title', $page->title);
	    $page->path = $request->input('path', $page->path);
	    $page->{'metadata->show_in_nav'} = !!$request->input('show_in_nav', $page->show_in_nav);
	    $page->{'metadata->template'} = $request->input('template', $page->template);
	    $page->{'metadata->redirect'} = $request->input('redirect', $page->redirect);
	    $page->save();
	    $page->content = $request->input('content', $page->content);
	    
	    return redirect()->back();
    }
    
    
    public function save_page_order(Request $request){
	    foreach($request->pages as $k => $v){
		    Page::find($k)->update(['sort_order' => $v]);
	    }
	    
	    return ["message" => "saved"];
    }
    
    
    public function destroy(Request $request, Page $page){
	    $page->delete();
	    return redirect()->route('pages.list');
    }
    
    public function storeUpload(Request $request){
		if(!$request->hasFile('upload'))
			abort(500);
			
		$requestfile = $request->file('upload');
	    if ($requestfile->isValid()){
			$originalname = $requestfile->getClientOriginalName();
			$md5 = md5_file($requestfile->path());
			$path = $requestfile->storeAs($md5, $originalname, [
				'CacheControl' => 'max-age=' . 30 * 24 * 60 * 60,
				'ContentType' =>  mime($originalname),
			]); // 30 day cache
			cache()->forget('cached_files');
			if($request->back) return redirect()->back();
			return [
				'uploaded' => 1,
				'filename' => $path,
				'url' => Storage::url($path)
			];
		};		    
    }
}