@extends('pages._parent')

@section('head')
<title>{{$page->title}} | Arlington Science Focus School PTA</title>
@endsection

@section('content')
<section id="fh_home" class="full-height bg-cover d-flex flex-column align-items-center justify-content-center text-center py-4" style="background-image: url(/images/meetings/volunteer.jpg); ">
	

    <span class="display-2 text-white letter-spacing mt-4" style="z-index: 1; max-width: 800px">We make a living by what we get, but we make a life by what we give.</span>
    
	<span class="display-4 text-white letter-spacing mt-3 mb-4" style="z-index: 1">WINSTON CHURCHILL</span>
    

</section>

<section>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			{!! $page->content !!}
			</div>
		</div>
	</div>	
</section>
<script>
	(function(){
		window.fh = document.getElementById('fh_home');
		var header = document.getElementById('header_outer');
		var h = header.clientHeight;
		fh.style.minHeight = 'calc(60vh - '+h+'px)';
		fh.style.opacity = '1';
	})();
</script>

@endsection
