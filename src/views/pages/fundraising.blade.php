@extends('pages._parent')

@section('head')
<title>{{$page->title}} | Arlington Science Focus School PTA</title>
@endsection

@section('content')
<section id="fh_home" class="full-height bg-cover d-flex flex-column align-items-center justify-content-center text-center" style="background-image: url(/images/fundraising/kids-art.jpg); ">
	

    <span class="display-1 text-white letter-spacing" style="z-index: 1">"It always seems impossible,<br>until it’s done."</span>
    
	<span class="display-4 text-white letter-spacing mt-3" style="z-index: 1">NELSON MANDELA</span>
    

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
