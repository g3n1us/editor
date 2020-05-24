<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,400i,600|Source+Sans+Pro:200,300,400,400i,700&amp;subset=latin-ext" rel="stylesheet">		
<!--
font-family: 'Josefin Sans', sans-serif;
font-family: 'Source Sans Pro', sans-serif;
-->
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@yield('head')
		<meta name="description" content="The website of the PTA of Arlington Science Focus School, a public elementary school that is part of the Arlington, Virginia Public School System. ">
		<meta name="keywords" content="school, education, PTA, parent, teacher">
@include('head_assets')

<style>
	#root a:not(.btn){
/* 		color: #f49116; */
/* 		font-size: 1.05rem; */
		font-weight: 400;
		position: relative;	
		transition: all .1s ease;
		display: inline-block;
		transform: translateY(0px);
	}
	#root a:not(.btn)::after{
		content: " ";
		position: absolute;
		bottom: 2px;
		left: 0;
		height: 3px;
		background-color: rgba(2, 2, 2, 0.2);
		width: 100%;
		transition: all .2s ease;
	}
	#root a:not(.btn):hover::after{
		height: 5px;
		bottom: 0;
		background-color: rgba(2, 2, 2, 0.5);


	}	
	#root a:not(.btn):hover{
		text-decoration: none;
		transform: translateY(-2px);
		
	}
</style>
		
	</head>
	<body>
@foreach($alerts as $alert)

		<button class="alert Xalert-danger w-100 text-left mb-0 mt-0 school_alert" hidden data-alert_id="{{$alert->id}}" style="background-color: #fff" type="button" data-toggle="modal" data-target="#alert-modal-{{$alert->id}}">
			<i class="fa fa-lightbulb-o fa-lg fa-fw"></i>
		{{$alert->subject}}
			<i class="fa fa-close ml-auto close text-muted" data-dismiss="alert" data-alert_id="{{$alert->id}}"></i>
		</button>	
		<div class="modal fade alert-modal" id="alert-modal-{{$alert->id}}" tabindex="-1" role="dialog" aria-labelledby="alert-modal-{{$alert->id}}" aria-hidden="true" style="overflow: hidden;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">{{$alert->subject}}</h4>
					</div>
					<div class="modal-body">
				        <div class="loading text-center mt-4"><i class="fa fa-spin fa-lg fa-cog"></i></div>
						
				        <iframe frameborder="0" scrolling="yes" style="width: 100%; height: 75vh; height: calc(75vh - 30px);" data-src="{{route('messages.raw', $alert->message)}}"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

@endforeach
		
    <!--Navigation bar-->

		<section class="flex-grow">
		@component('components.navbar') 
@include('nav-items')		
		@endcomponent
			
			<div style="max-width: 800px; margin: auto; position: relative;" class="alerts">
				<div class="alert alert-info">{!!session('info')!!}</div>
				<div class="alert alert-danger">{!!session('error')!!}</div>
				<div class="alert alert-success">{!!session('message')!!}</div>
			</div>
			<div id="root" class="mb-5">
			@yield('content')			
			</div>
			
		</section>
    <!--Footer-->
    
    
    <footer id="footer" class="footer py-2 bg-danger bg-inverse text-white" style="font-weight: 200">
      <div class="container text-center">
        <span class="mx-2 my-2 d-block d-md-inline">©{{date('Y')}} ASFS PTA. All rights reserved</span>  
        <span class="mx-2 my-2 d-block d-md-inline">1501 N. Lincoln St. Arlington, VA 22201 </span>
        <span class="mx-2 my-2 d-block d-md-inline"> <a class="text-white" href="tel:703-228-7870">703-228-7670</a> fax: 703-525-2452</span>
        <span class="mx-2 my-2 d-block d-md-block">Custom Application by: <a class="text-info" href="http://americanmadeweb.com" target="_blank">AmericanMadeWeb</a></span>
        <span class="mx-2 my-2 d-block d-md-block">Site Design by: <a class="text-info" href="https://www.jmbdc.com/" target="_blank">JMB Design Center</a></span>
        
        
      </div>

    </footer>
    <!--/ Footer-->

@include('foot_assets')
<script>
	$('[href="'+window.location.pathname+'"]').addClass('active');
	$('.remove-inline-styles').each(function(){
		$(this).find('[style]').removeAttr('style');
	});
	$(document).on('click', '.alert .close', function(e){
		e.stopPropagation();
		localStorage['hidden_asfs_alert_' + $(this).data('alert_id')] = 1;
	});
	$('.school_alert').each(function(){
		if(!localStorage['hidden_asfs_alert_' + $(this).data('alert_id')])
			$(this).removeAttr('hidden');
	});
</script>
  </body>
</html>