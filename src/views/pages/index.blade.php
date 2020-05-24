@extends('pages._parent')

@section('head')
<title>{{$page->title}} | Arlington Science Focus School PTA</title>
@endsection

@section('content')

<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">
			{!! $page->content !!}
		</div>
	</div>
</div>

@endsection
