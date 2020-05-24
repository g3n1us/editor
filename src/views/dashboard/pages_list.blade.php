@extends('g3n1us_editor::dashboard.layout')

@section('content')

<div class="container my-5">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<form action="{{request('pages.create')}}" class="mb-4" method="post">
				{{csrf_field()}}
				{{method_field('put')}}
				<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Page</button>
			</form>
			
			<div id="page_list" class="list-group">
				@forelse($pages as $list_page)
				<a href="{{ route('pages.edit', $list_page) }}" data-id="{{$list_page->id}}" data-sort_order="{{$list_page->sort_order}}" class="d-flex list-group-item justify-content-between mb-1">
					<span>{{ $list_page->title }}</span> <small class="text-muted mr-auto ml-2 xorder-last xorder-md-unordered">{{$list_page->path}}</small>
					<i class="fa fa-bars fa-lg text-muted Xorder-first Xorder-md-unordered" style="cursor: move; opacity: .3"></i>
				</a>
				@empty
				<div class="alert alert-info">No pages yet!</div>
				@endforelse
			</div>
			
			<p class="my-4 text-muted text-right">Reorder pages by dragging <br> <button type="button" id="savesort" class="btn btn-primary mt-2" hidden><i class="fa fa-save"></i> Save Page Order</button></p>
			
		</div>
	</div>
</div>

@endsection
