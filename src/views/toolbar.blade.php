@if(isset($page) && is_object($page))
<style >
  .cke_top{
    position: sticky;
    top: 58px;
  }
  .w-auto{
    width: auto;
  }
</style>
@auth
<form method="post" id="save_editing_form" style="display: none;">
	{{csrf_field()}}
<!--
	<input name="name" type="text" required>
	<input name="description" type="text" required>
-->
	<input name="html" type="text" required>
</form>
<form id="logout_form" method="post" action="/logout">
  {{ csrf_field() }}
</form>

<nav id="admin-navbar" class="admin-navbar navbar navbar-expand bg-light navbar-light sticky-top">
  <div class="navbar-nav flex-grow flex-wrap" style="flex-grow: 1">
@if(isset($edit_mode))
	
  	<a class="nav-item nav-link cue-in" href="."><i class="fa fa-arrow-circle-left"></i>  Exit<span class="d-none d-md-inline"> Edit Mode</span></a>
  	<button type="submit" form="save_editing_form" class="btn btn-sm btn-success" style="cursor: pointer"><i class="fa fa-save"></i> Save</button>
  	<a class="nav-item nav-link" href="#options-navbar-parent" data-toggle="collapse" title="Options"><i class="fa fa-cog"></i>  Options</a>
  	
  	
<!--
  	<label style="align-self: center" class="mb-0 nav-item nav-link">
    	<input type="hidden" form="save_editing_form"  name="metadata[hidden_from_nav]" value="0">
    	<input type="checkbox" form="save_editing_form" @if($page->metadata && $page->metadata->hidden_from_nav) checked @endif  name="metadata[hidden_from_nav]" value="1">
    	Hide Page from Nav
  	</label>
-->
@else    
    <a class="nav-item nav-link" href="{{str_finish(str_start(request()->path(), '/'), '/')}}edit"><i class="fa fa-edit"></i> Edit</a>
    
@endif
    
    <a class="nav-item nav-link mr-auto" onclick='window.location = prompt("Enter URL") + "/create"' href="#"><i class="fa fa-plus"></i> New Page</a>
    
    <a class="nav-item nav-link text-muted ml-auto" href="#" onclick="if(!confirm('Are you sure?')) return false; window.location.assign(window.location.pathname + '/delete'); return false;"><i class="fa fa-trash"></i> Delete Page</a>
    <button type="submit" form="logout_form" class="nav-item nav-link text-muted"><i class="fa fa-arrow-circle-left"></i> Logout</button>
    
  </div>
</nav>
<div id="options-navbar-parent" class="collapse">
<nav class="options-navbar navbar navbar-expand bg-light navbar-light sticky-top">
  <div class="navbar-nav flex-grow flex-wrap" style="flex-grow: 1">
  	<label style="align-self: center" class="mb-0 nav-item nav-link">
    	<input type="hidden" form="save_editing_form"  name="metadata[hidden_from_nav]" value="0">
    	<input type="checkbox" form="save_editing_form" @if($page->metadata && $page->metadata->hidden_from_nav) checked @endif  name="metadata[hidden_from_nav]" value="1">
    	Hide Page from Nav
  	</label>
  	<span class="nav-item nav-link">Page Title: </span> <input class="form-control d-inline-block w-auto" form="save_editing_form" type="text" name="title" value="{{$page->title}}">
  	
  	<span class="nav-item nav-link">Parent Page: </span> 
  	<select class="form-control d-inline-block w-auto" form="save_editing_form" style="max-width: 100px" name="parent_page_id" value="{{$page->parent_page_id}}">
    	<option value="">--</option>    	
    	@foreach(\G3n1us\Editor\Page::pluck('id', 'path') as $pname => $pid)
    	<option value="{{$pid}}" @if($pid == $page->parent_page_id) selected @endif>{{$pname}}</option>
    	@endforeach
  	</select>
  	
  	
  	<span class="align-self-center h5 my-0 ml-auto">
  	Tags: 
  	@foreach($page->tags as $tag)
  	{!! $tag !!}
  	@endforeach
  	</span>
    <a class="nav-item nav-link" onclick='prompt("Enter Tag Name"); alert("Well, the plumbing is in place for this feature, but we&apos;re not quite finished :(")' href="#"><i class="fa fa-plus"></i> Add Tag</a>
  	
  </div>
</nav>
</div>
@endauth	


@push('scripts')
<script>
  $('[data-toggle="popover"]').popover();
</script>
@endpush
@endif