<?php


if(!function_exists('output_nav')){
function output_nav(){

//       $pages = G3n1us\Editor\Page::where('path', 'not like', '%/%')->get();
  $pages = G3n1us\Editor\Page::whereNull('parent_page_id')
    ->where('metadata->hidden_from_nav', '!=', "1")
    ->orWhereNull('metadata')
    ->get();
  return $pages;
}
}
	
	

if(!function_exists('person_hash')){
	function person_hash($person){
		$str = "";
		$str .= substr(array_get($person, 'first_name'), 0, 5);
		$str .= substr(array_get($person, 'middle_name'), 0, 5);
		$str .= substr(array_get($person, 'last_name'), 0, 5);
		return md5(strtolower($str));
	}
}




if(!function_exists('to_csv')){
	function to_csv(\Illuminate\Support\Collection $collection){
		$headers = array_keys($collection->first());
		$rows = $collection->prepend($headers);
		$tmpname = '/tmp/' . rand();
		$out = fopen($tmpname, 'w');
		foreach($rows as $row){
			fputcsv($out, $row);
		}
		fclose($out);
		return file_get_contents($tmpname);
	}
}

	
if(!function_exists('sanitize_phone')){
	function sanitize_phone( $phone, $international = false ) {
		$format = "/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/";
		
		$alt_format = '/^(\+\s*)?((0{0,2}1{1,3}[^\d]+)?\(?\s*([2-9][0-9]{2})\s*[^\d]?\s*([2-9][0-9]{2})\s*[^\d]?\s*([\d]{4})){1}(\s*([[:alpha:]#][^\d]*\d.*))?$/';
	
		// Trim & Clean extension
	    $phone = trim( $phone );
	    $phone = preg_replace( '/\s+(#|x|ext(ension)?)\.?:?\s*(\d+)/', ' ext \3', $phone );
	
	    if ( preg_match( $alt_format, $phone, $matches ) ) {
	        return '(' . $matches[4] . ') ' . $matches[5] . '-' . $matches[6] . ( !empty( $matches[8] ) ? ' ' . $matches[8] : '' );
	    } elseif( preg_match( $format, $phone, $matches ) ) {
	
	    	// format
	    	$phone = preg_replace( $format, "($2) $3-$4", $phone );
	
	    	// Remove likely has a preceding dash
	    	$phone = ltrim( $phone, '-' );
	
	    	// Remove empty area codes
	    	if ( false !== strpos( trim( $phone ), '()', 0 ) ) { 
	    		$phone = ltrim( trim( $phone ), '()' );
	    	}
	
	    	// Trim and remove double spaces created
	    	return preg_replace('/\\s+/', ' ', trim( $phone ));
	    }
	
	    return false;
	}
}

if(!function_exists('eenv')){
	function eenv($val){
		
	}
}

if(!function_exists('get_permissions')){
	function get_permissions($user_or_email = null){
		$config = config('app.user_permissions');
		$config = str_replace('\\n', "\n", config('app.user_permissions'));
		$perms_array = explode('~', $config);
		$perms_array = array_filter($perms_array);
		
		$perms_array = array_map(function($v){
			$split = array_filter(explode('|', $v));
			$perms = explode(',', $split[1]);
			$perms = array_map('trim', $perms);
			return [trim($split[0]) => $perms];
		}, $perms_array);
		$perms_array = array_collapse($perms_array);
		if(!is_null($user_or_email)){
			if($user_or_email instanceof \App\User){
				$email = $user_or_email->email;
			}
			else{
				$email = $user_or_email;
			}
			return array_get($perms_array, $email, []);
		}
		return $perms_array;
		
	}
}



if(!function_exists('tidy_title')){
	function tidy_title($snake_string){
		$str = str_replace('_', ' ', title_case($snake_string));
		// additional changes
		$str = preg_replace('/asfs/i', 'ASFS', $str);
		return $str;
	}	
}



if(!function_exists('page_list')){
	function page_list($nav = true){
		if($nav)
			return \G3n1us\Editor\Models\Page::where('metadata->show_in_nav', true)->orderBy('sort_order')->get();
		else
			return \G3n1us\Editor\Models\Page::orderBy('sort_order')->get();
	}
	
}



if(!function_exists('ordinal')){
	function ordinal($number) {
		if(empty($number)) return $number;
		if($number < 0) return $number;
	    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
	    if ((($number % 100) >= 11) && (($number%100) <= 13))
	        return $number. 'th';
	    else
	        return $number. $ends[$number % 10];
	}	
}



if(!function_exists('archive_paths')){
	function archive_paths(){
	    $allfiles = Storage::disk('archive')->allFiles();
	    return paths_to_array($allfiles);
	}	
}



if(!function_exists('paths_to_array')){
	function paths_to_array($paths){
	    $return = [];
		
	    $alldirectories = array_values(array_unique(array_map('dirname', $paths)));
	    sort($alldirectories);
	    foreach($alldirectories as $dir){
		    array_set($return, str_replace('/', '.', $dir), []);
	    }
	    $return = array_collapse($return);
	    
// 	    dump($return);
	    foreach($paths as $file){
		    if(starts_with($file, '.'))
			    continue;
		    $fileparts = explode('/', $file);
// 		    $filename = array_pop($fileparts);
		    $fileparts = array_filter($fileparts);
// 		    dump('$fileparts', $fileparts, basename($file));
		    $dotpath  = implode('.', $fileparts);
// 		    dump('DOTPATH', $dotpath);
		    
		    $directory_array = array_get($return, $dotpath);
		    $directory_array[] = $file;
// 		    dump('DIRECTORU_ARRAY', $directory_array);
		    if(!array_key_exists($dotpath, $return))
			    array_set($return, $dotpath, $directory_array);
	    }
// 	    dd($return);
	    return $return;
	}
}



/*
	
	// commented out because i don't think it is being used. Add back if something breaks.
	
	
if(!function_exists('output_nav')){
	function output_nav($input){
		$output = '';
		foreach($input as $i){
			if(is_array($i))
				$output .= output_nav($i);
			else
				$output .= '<a class="list-group-item mb-1 text-white" href="'.$i.'">'.$i.'</a>';
		}
		return $output;
	}
}
*/


/*
if(!function_exists('existing_skus')){
	function existing_skus(){
	    return \App\Purchase::select('request->items as i')->get()->map(function($v){
		    return json_decode($v->i, true);
	    })->collapse()->pluck('sku')->unique();		
	}
}
*/
		
if(!function_exists('nav')){
	function nav(array $data) {
		$data = array_filter($data, function($v){
			if(is_array($v))
				return key($v) !== "";
			else return true;
		});
	    $html = '<ul>';
	    foreach ($data as $k => $v) {
	        if (is_array($v)) {
	            $html .=  "<li>$k" . nav($v) . "</li>";
	        }
	        else {
		        if(!str_contains(mime($v), 'image') && !starts_with(basename($v), '.'))
		            $html .= '<li><a href="/'.$v.'">'.basename($v).'</a></li>';
	        }
	    }
	    $html .= '</ul>';
	    return $html;
	}	
}



if(!function_exists('dir_array')){
	function dir_array($dirname){
		$iterator = new \DirectoryIterator($dirname);
		$return = [];
		foreach ($iterator as $fileInfo) {
		    if($fileInfo->isDot()) continue;
		    $return[] = $fileInfo->getFilename();
		}
		return $return;
	}
}



if(!function_exists('dir_json')){
	function dir_json($dirname){
		return json_encode(dir_array($dirname));
	}
}



if(!function_exists('empty_get')){
	function empty_get($obj, $key, $default = null){
		$val = data_get($obj, $key);
		return empty(trim($val)) ? $default : $val;
	}
}



if(!function_exists('real_rand')){
	function real_rand($length = 22){
		$rand_text = str_random(200);
		$rand_int  = rand().rand().rand();
		$spec_chars = '!@#$%^&*()?[]{}!@#$%^&*()?[]{}!@#$%^&*()?[]{}!@#$%^&*()?[]{}';
		return str_limit(str_shuffle("$rand_text$rand_int$spec_chars"), $length, '');
	}	
}



if(!function_exists('mime')){
	function mime($path){
		$path = strtolower($path);
		if(ends_with($path, ".css")) $mime = "text/css";
		else if(ends_with($path, ".less")) $mime = "text/css";
		else if(ends_with($path, ".sass")) $mime = "text/css";
		else if(ends_with($path, ".scss")) $mime = "text/css";
		else if(ends_with($path, ".mp4")) $mime = "video/mp4";
		else if(ends_with($path, ".m4v")) $mime = "video/mp4";
		else if(ends_with($path, ".mov")) $mime = "video/quicktime";
		else if(ends_with($path, ".js")) $mime = "application/javascript";
		else if(ends_with($path, ".pdf")) $mime = "application/pdf";
		else if(ends_with($path, ".svg")) $mime = "image/svg+xml";
		else if(ends_with($path, ".jpg")) $mime = "image/jpeg";
		else if(ends_with($path, ".jpeg")) $mime = "image/jpeg";
		else if(ends_with($path, ".png")) $mime = "image/png";
		else if(ends_with($path, ".gif")) $mime = "image/gif";
		else if(ends_with($path, ".ico")) $mime = "image/vnd.microsoft.icon";
		else if(ends_with($path, ".json")) $mime = "application/json";
		else if(ends_with($path, ".ttf")) $mime = "application/x-font-truetype";
		else if(ends_with($path, ".woff")) $mime = "application/font-woff";
		else if(ends_with($path, ".woff2")) $mime = "application/font-woff2";
		else if(ends_with($path, ".otf")) $mime = "application/x-font-opentype";
		else if(ends_with($path, ".eot")) $mime = "application/vnd.ms-fontobject";
		else if(ends_with($path, ".md")) $mime = "text/markdown; charset=UTF-8";
		else if(ends_with($path, ".swf")) $mime = "application/x-shockwave-flash";
		else if(ends_with($path, ".php")) $mime = "text/plain";
		else if(ends_with($path, ".doc")) $mime = "application/msword";
		else if(ends_with($path, ".docx")) $mime = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
		else if(ends_with($path, ".pptx")) $mime = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
		else if(ends_with($path, ".xls")) $mime = "application/vnd.ms-excel";
		else if(ends_with($path, ".xlsx")) $mime = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
		else if(ends_with($path, ".key")) $mime = "application/x-iwork-keynote-sffkey";
		else if(ends_with($path, ".pages")) $mime = "application/x-iwork-pages-sffpages";
		else if(ends_with($path, ".numbers")) $mime = "application/x-iwork-numbers-sffnumbers";
		
			
		else{
			$mime = "text/html";
		}
		return $mime;
	}		
}

if(!function_exists('file_icon')){
	function file_icon($path){
		$path = strtolower($path);
		if(ends_with($path, ".css")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".less")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".sass")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".scss")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".mp4")) $mime = "fa-file-movie-o";
		else if(ends_with($path, ".mov")) $mime = "fa-file-movie-o";
		else if(ends_with($path, ".m4v")) $mime = "fa-file-movie-o";
		else if(ends_with($path, ".avi")) $mime = "fa-file-movie-o";
		else if(ends_with($path, ".js")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".pdf")) $mime = "fa-file-pdf-o";
		else if(ends_with($path, ".svg")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".jpg")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".jpeg")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".png")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".gif")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".ico")) $mime = "fa-file-image-o";
		else if(ends_with($path, ".json")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".ttf")) $mime = "fa-font";
		else if(ends_with($path, ".woff")) $mime = "fa-font";
		else if(ends_with($path, ".woff2")) $mime = "fa-font";
		else if(ends_with($path, ".otf")) $mime = "fa-font";
		else if(ends_with($path, ".eot")) $mime = "fa-font";
		else if(ends_with($path, ".md")) $mime = "fa-file-word-o";
		else if(ends_with($path, ".swf")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".php")) $mime = "fa-file-code-o";
		else if(ends_with($path, ".doc")) $mime = "fa-file-word-o";
		else if(ends_with($path, ".docx")) $mime = "fa-file-word-o";
			
		else{
			$mime = "fa-file-o";
		}
		return $mime;
	}		
}

if(!function_exists('is_image')){
	function is_image($path){
		return str_contains(mime($path), 'image');
	}

}

if(!function_exists('only_vowels')){
	function only_vowels($string){
		preg_match_all('/([a-zA-Z])/', strtolower($string), $vowels);
		$vowels = array_get($vowels, 1, []);
		sort($vowels);
		return implode('', $vowels);
	}
}

if(!function_exists('is_even')){
	function is_even($number){
		return $number % 2 == 0;
	}
}

if(!function_exists('files')){
	function files(){
		return cache()->remember('cached_files', 60 * 24 * 7, function(){
			$trash = Storage::disk('trash')->allFiles();
			$files = collect(Storage::allFiles())->filter(function($f){
				return !starts_with($f, '.');
			});
			$files = $files->diff($trash);
			$files->transform(function($file){
				$fileobj = [];
				$fileobj['mime'] = mime($file);
				$fileobj['icon'] = file_icon($file);
				$fileobj['is_image'] = is_image($file);
				$fileobj['filename'] = basename($file);
				$fileobj['url'] = url(Storage::url($file));
				$fileobj['path'] = Storage::url($file);
				$fileobj['delete_path'] = $file;
				$fileobj['last_modified'] = Storage::lastModified($file);
				$fileobj['last_modified_string'] = \Carbon\Carbon::createFromTimestamp($fileobj['last_modified'])->toDateTimeString();
				
				return $fileobj;
			});
			$files = $files->sortByDesc('last_modified');
			return $files->values();
		});
		
	}
}

if(!function_exists('navbar')){
	function navbar(){
// 		dd(page_list(true));
		$arr = page_list()->keyBy('path');
		$keys = $arr->keys()->toArray();
		$a = paths_to_array($keys);
		$paths = collect(array_filter($a));
		
		$paths->transform(function($v, $k) use($arr){
			if(count($v) > 1){
				return array_map(function($arv) use($arr){
					return $arr->get(head($arv));
				}, $v);
			}
			else{
				$find = $v;
				while(is_array($find)){
					$find = head($find);
				}
				return $arr->get($find);
			}
		});
		
		$paths = $paths->sortBy(function ($item, $key) {
			if(is_array($item))
				return head($item)->sort_order;
			else
			    return $item->sort_order;
		});
		
		$html = "";
		foreach($paths as $key => $p){
			if(is_array($p)){
				$href = str_start($key, '/');
				$pagename = $key;
				$possible_page = $arr->get($href);
				$onclick = '';		
				if($possible_page){
					$pagename = $possible_page->title;
					$href = $possible_page->path;
					$onclick = 'onclick="window.location.assign(\''. $href .'\')"';
				}

				$html .= "
				<div class='dropdown'>
			        <a class='nav-item nav-link dropdown-toggle' $onclick href='$href' id='{$key}navbarDropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
			          $pagename
			        </a>
			        <div class='dropdown-menu' aria-labelledby='{$key}navbarDropdownMenuLink'>";
				foreach($p as $subp){
					$html .= "<a class='dropdown-item' href='{$subp->path}'>{$subp->title}</a>";
				}
			          
		        $html .= "</div>
				</div>";
			}
			else{
				$html .= "<a class='nav-item nav-link px-3' href='{$p->path}'>{$p->title}</a>";
			}
		}
		return $html;
	}	
}
