@extends('pages._parent')

@section('head')
<title>{{$page->title}} | Arlington Science Focus School PTA</title>

<style>
	.not_edit_mode .visible_edit_mode{
		display: none !important;
	}
</style>
@endsection

@section('content')
@if(isset($is_home_page))
@if($edit_mode)
<div id="home" class="edit_mode">
@else
<div id="home" class="not_edit_mode">	
@endif
@endif
<section class="video-bg" id="video-bg" style="background-image: url(/images/homepage_images/homepage1.jpg); background-size: cover; background-position: center;">
	
@if($edit_mode)	
<!--
	<video id="video" class="fade-in-dramatic" autoplay loop>
		<source src="/images/short_drone_asfs2.mp4" type="video/mp4">
	</video>
-->	
@endif

<!--
	<div class="video-bg--overlay"></div>
	<div class="video-bg--close btn btn-primary"></div>
-->
</section>
<section id="fh_home" class="full-height XXbg-cover d-flex flex-column align-items-center justify-content-center text-center" style="background-color: transparent">
<!-- 	style="background-image: url(/images/home/home_bg_40percent.jpg)" -->
	<span class="display-4 text-warning letter-spacing mb-4 text-bg-white" style="z-index: 1"><span>ASFS PTA</span></span>

    <span class="display-1 text-warning text-bg-white" style="z-index: 1"><span>ONE TEAM</span></span>
    
	<span class="display-4 text-warning letter-spacing mt-4 text-bg-white" style="z-index: 1"><span>TOGETHER EVERYONE ACHIEVES MORE</span></span>

</section>

<section>
	<div class="container mt-4">
		<div class="row">
			<div class="col-md-12">
			{!! $page->content !!}
			</div>
		</div>
	</div>	
</section>

<section>
	<div class="container mt-4">

		<div class="row">
			<div class="col-md-8" id="callouts">
				<div class="mb-4 card-deck">
					{!! callout(array_get($callouts, 0, [])) !!}
					{!! callout(array_get($callouts, 1, [])) !!}
				</div>
				<div class="card-deck">
					{!! callout(array_get($callouts, 2, [])) !!}
					{!! callout(array_get($callouts, 3, [])) !!}
				</div>
			</div>
			<div class="col-md-4">
@if(request()->edit_mode == true)
<button type="button" class="btn btn-danger add-feed-item"><i class="fa fa-plus"></i> Add Feed Item</button>
<script>
	window.feed_item_data = {!! $feed_items or [] !!};
</script>
<script type="text/ignored-template" id="feed-item-template">
	{!! feed_item([]) !!}
</script>
@endif				
				<div class="feed-items">
				@foreach($feed_items as $feed_item)
				
				{!! feed_item($feed_item) !!}
				
				@endforeach
				</div>
			</div>
		</div>
	</div>	
</section>
</div>
<script>
	
	window.homepage = {!!$homepage!!};
	
	(function(){
		window.fh = document.getElementById('fh_home');
		window.vidbg = document.getElementById('video-bg');
		var header = document.getElementById('header_outer');
		vidbg.style.height = 'calc(70vh - '+header.clientHeight+'px)';
		fh.style.minHeight = 'calc(70vh - '+header.clientHeight+'px)';
		fh.style.opacity = '1';
	})();
	
	(function(){
		var vid = document.getElementById('video');
		if(!vid) return;
		vid.playbackRate = 0.5;
		vid.classList.add('loaded');
		setTimeout(function(){
			vid.pause();
		}, 20000)
	})();
	
</script>

@endsection

