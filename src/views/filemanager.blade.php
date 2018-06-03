<!DOCTYPE HTML>
<html class="editor-bootstrap">
	<head>
		<title>Filemanager</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script>
			window.formErrors = {!! isset($errors) ? $errors->toJSON() : '{}' !!};
	    window.csrf_token = '{{ csrf_token() }}';
		</script>
		<style>

		.smartpop:empty{
			right: 400px;
		}
		.cke_top{
			position: sticky !important;
			top: 0;
		}
		.navbar-brand img{
			width: 50px;
		}
		
		@media(min-width: 768px){
			.navbar-brand img{
				width: 180px;
			}
		}
		
		</style>    
		
    <!-- <link href="/js/dropzone/dropzone.css" rel="stylesheet"> -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
    <!-- <link href="/_assets/video-js-6.2.4/video-js.css" rel="stylesheet"> -->
		<script>
			if(opener && opener.filemanagerwindow)
				setInterval(function(){ opener.filemanagerwindow = window; }, 500); 
		</script>
		<style>
			.tab-content > .active {
			    display: flex;
			}
			.nav-link.active{
				pointer-events: none;
			}
			.pagination-links-outer{
				flex-basis: 100%;
			}
			.fa-4x{
				font-size: 8em;
			}
		</style>
	</head>
	<body>
		<div id="main">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs nav-justified mt-2" role="tablist">
						<li class="nav-item">
							<a class="nav-link" href="#files" data-model="files" role="tab">Files</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#pages" data-model="pages" role="tab">Pages</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="#upload" role="tab">Upload</a>
						</li>

					</ul>
					
				</div>
			</div>
		</div>
		<!-- Tab panes -->
		<div class="tab-content container-fluid">
			<div class="row tab-pane pt-2 justify-content-center" id="files" role="tabpanel"><div class="mt-5 text-center"><i class="fa fa-2x fa-cog fa-spin"></i><br>Files</div></div>
			<div class="row tab-pane pt-2 justify-content-center" id="pages" role="tabpanel"><div class="mt-5 text-center"><i class="fa fa-2x fa-cog fa-spin"></i><br>Pages</div></div>
			<div class="row tab-pane pt-2 justify-content-center" id="upload" role="tabpanel">
			<form id="upload_form" class="mt-4" action="/editor_dashboard/upload" method="post" enctype="multipart/form-data">
  			<input type="file" multiple name="upload[]">
  			{{csrf_field()}}
  			<input type="hidden" name="redirect" value="true">
  			<button class="btn btn-info">Submit</button>
			</form>
			
			</div>
					
		</div> <!-- tab-content -->
		</div>


@verbatim
<script type="text/template" id="files">
{{#each this as |file|}}
<div class="col mb-2" style="max-width:280px;min-width:280px;">
	<div class="card mb-2">
		
		{{#if is_image}}
		<img class="card-img-top img-fluid align-self-center" ondragstart="dragstart_handler(event)" data-path="{{file.url}}" src="{{file.url}}">
		{{else}}
		<i class="mt-2 fa {{file.icon}} fa-4x card-img-top img-fluid align-self-center" data-title="{{file.url}}" src="{{file.url}}" draggable="true" ondragstart="dragstart_handler(event)" data-path="{{file.url}}" data-draghandler="url"></i>
		
		<div class="text-center"><a href="{{file.url}}" target="_blank"><i class="fa fa-eye"></i> Preview</a></div>
		{{/if}}
		<div class="card-block card-body text-muted">
			<p title="{{file.filename}}" class="sans-serif small" style="white-space: nowrap; overflow: hidden; margin-bottom: 5px; font-family: sans-serif">
			{{file.filename}}
			</p>
			<input type="text" readonly="" onfocus="this.select()" class="text-muted d-block" value="{{file.url}}">
			<small title="Added on: {{file.last_modified_string}}" class="clearfix"><small class="small text-muted">{{file.last_modified_string}}</small></small>
			<i class="fa fa-trash text-muted ml-auto float-right" style="opacity: .4" onclick="if(!confirm('Are you sure?'))return false; deleteFile('{{file.id}}')"></i>
		</div>
	</div>
</div>
{{/each}}
<!-- </div>	 -->
	
</script>


<script type="text/template" id="pages">
{{#each this as |page|}}
<div class="col mb-2" >
	<div class="card mb-2" draggable="true" ondragstart="dragstart_handler(event)" data-draghandler="url" data-path="{{page.path}}">
		<div class="card-block text-muted">
			<p class="sans-serif" style="white-space: nowrap; overflow: hidden; margin-bottom: 5px; font-family: sans-serif">
			{{page.title}} {{page.path}}
			</p>
			<input type="text" readonly="" onfocus="this.select()" class="text-muted" value="{{page.path}}">
		</div>
	</div>
</div>
{{/each}}
</div>	
	
</script>


@endverbatim		

@verbatim
<script type="text/template" id="checkout">
	<div class="card shopping-cart-card my-2">
		<div class="card-header">
			Shopping Cart
		</div>
		<div class="card-body card-block">
			{{#each items as |item key|}}
			<div class="list-group-item">
			{{item.description}} <a onclick="MyCart.removeItem('{{key}}')" class="ml-auto"><i class="fa fa-trash"></i></a>
			</div>
			{{else}}
			  <p class="text-danger">Your cart is empty</p>			
			{{/each}}
			<div class="text-right">
				<h5 class="mt-4">Total: {{total_string}}</h5>
				<button type="button" class="btn btn-success checkout">Checkout</button>
			</div>
			
		</div>
	</div>
</script>

<script type="text/template" id="checkout_processing">
	<div class="my-4 text-center">
			<i class="fa fa-cog fa-spin fa-2x"></i>
			<h4 class="mt-2">Processing...</h4>
	</div>
</script>

<script type="text/template" id="checkout_thanks">
	<div class="card card-outline-success my-4">
		<div class="card-body card-block ">
			<h3 class="text-center"><i class="fa fa-thumbs-o-up fa-4x"></i> <br> Thank you!</h3>
			<h4 class="mt-3">You will receive a receipt/tax form via email.</h4>
			
		</div>
	</div>
</script>

<script type="text/template" id="checkout_error">
	<div class="card card-outline-danger my-4">
		<div class="card-body card-block ">
			<h3 class="text-center text-muted">There was a problem processing your card. </h3>
			<h4 class="mt-3 text-muted">Make sure you've entered the information correctly and try again.</h4>
		</div>
	</div>
	<div class="cart mb-3"></div>
</script>

@endverbatim
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-migrate-3.0.0.js"></script>	
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
	
<!-- 	<script src="/CORE_JS/js/g3n1us_helpers.js?v="></script> -->
	
@if(auth()->check())
	<script>
		window.current_user_email = '{{ auth()->user()->email }}';
	</script>
@include('g3n1us_editor::handlebars_templates')
@else
	<script>
		window.current_user_email = false;
	</script>
@endif
	
	
	
<script>
	$('#create_page input#title').on('input', function(e){
		$('#create_page input#path').val('/'+str_slug(this.value));
	});
	$(document).on('focus', '#create_page input#path', function(e){
		$('#create_page input#title').off('input');
	});
</script>


@include('g3n1us_editor::index_js')
@include('g3n1us_editor::g3n1us_helpers')
		
<script>
  


function previewFile(el) {
	console.log(el);
	var preview = $($(el).data('image_target'))[0];
	var file    = el.files[0];
	var reader  = new FileReader();
	
	reader.addEventListener("load", function () {
		preview.src = reader.result;
	}, false);
	
	if (file) {
		reader.readAsDataURL(file);
	}
}	


var csrf_token = csrf_token || '{{csrf_token()}}';
	
function deleteFile(file_id){
  $.ajax({
      url: '/editor_dashboard/trash',
      type: 'DELETE',
      data: {file_id: file_id, _token: csrf_token},
      success: function(data) {
    		console.log(data);
    		if(data.success)
    			window.location.reload();
    		else
    			alert('error!!');
      }
  });  
}	
	
$(document).on('click', '[data-article_id]', function(e){
	e.preventDefault();
	opener.targetinput.val($(this).data('article_id'));
	window.close();
});



var loc = {hash: window.location.hash};

var LOCALSTORAGE_ENTRY_PREFIX =  'g3n1usFilemanagerTabCurrentPaginationPage_';

var localstorage_entry = localStorage[LOCALSTORAGE_ENTRY_PREFIX + window.location.hash.substring(1)];

if(!loc.hash.length)
	window.location.hash = $('.nav .nav-item').first().find('.nav-link')[0].hash.substring(1);

loc.watch('hash', function(prop, oldvalue, newvalue){
	var hash_trimmed = starts_with(newvalue, '#') ? newvalue.slice(1) : newvalue;
	localstorage_entry = LOCALSTORAGE_ENTRY_PREFIX+hash_trimmed;
	console.log(localstorage_entry);
	$('[href="#'+hash_trimmed+'"]').tab('show');
});


$(window).on('load hashchange', function(e){
	e.preventDefault();
	loc.hash = window.location.hash;
});



// alert(file_icon('dog.png'));

$('.nav-tabs .nav-link').on('shown.bs.tab', function (e) {
	if($(e.target).data('initial_load_complete'))
		return;
	$(e.target).data('initial_load_complete', true);
	var $tabpane = $(e.target.hash);
	var model = $(e.target).data('model') || false;
	if(!$tabpane.length || !model) 
		return;
		
	var currentpage = object_get(localStorage, LOCALSTORAGE_ENTRY_PREFIX + e.target.hash.substring(1), 1);
	var url = '/api/'+ model +'?page='+currentpage;
	$.getJSON(url)
    .then(function(data){
			data = data.data.map(function(v,k){
  			v.is_image = is_image(v.filename);
  			v.icon = file_icon(v.filename);
  			return v;
			});
			console.log('data', data);
			
// 			console.log(handlebars_templates[model](data));
			$tabpane.html(handlebars_templates[model](data));
		});
	
	
// 	console.log(e.relatedTarget) // previous active tab
});

$(document).on('click', '.pagination-links-outer .page-link', function(e){
	e.preventDefault();
	var $_get = parse_str(parse_url(e.target.href).search);
	localStorage[localstorage_entry] = object_get($_get, 'page', 1);
	console.log(localstorage_entry);
	 
	$('.tab-pane.active').load(e.target.href);
});
			
			
// ckeditor interactions
	
	
function getUrlParam(paramName, search){
	var search = search || window.location.search;
	var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	var match = search.match(reParam) ;
	
	return (match && match.length > 1) ? match[1] : '';
}	

$(document).on('click', '[data-path]', function(e){
	e.preventDefault();
	var funcNum = getUrlParam('CKEditorFuncNum', $(this).data('search'));
	var fileUrl = $(this).data('path');
	
	if(funcNum != ""){
		window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
		window.self.close();					
	}
});
	
function dragstart_handler(ev) {
	console.log(ev);
	$dragee = $(ev.target);
	var handler = $dragee.data('draghandler') || 'best_guess';
	console.log(handler);
	ev.target.src = $dragee.data('path');
	console.log($(ev.target).data('path'));
	(new drag_type_handlers)[handler].call($dragee, ev);
}

function drag_type_handlers(){
	// these functions are called dynamically and should modify the event.dataTransfer via setData
	this.url = function(event){
		console.log(this);
		event.dataTransfer.setData("text/html", '<a href="'+event.target.src+'">' + $(this).data('title') + '</a>');		
	}
	
	this.best_guess = function(event){
		if(is_video(event.target.src)){
			event.dataTransfer.setData("text/plain", event.target.src);		
		}
		else{
			console.log($(event.target));
			event.dataTransfer.setData("text/html", event.target.outerHTML);
		}
	}
	
}
			
			
			
		</script>
		
	</body>
</html>



