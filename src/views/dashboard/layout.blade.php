<!DOCTYPE html>   
<html lang="en" class="no-js" style="-webkit-overflow-scrolling: touch;">
<head>
	<meta charset="utf-8">
	<!--[if IE]><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>{{$title ?? ''}}</title>
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,400i,600|Source+Sans+Pro:200,300,400,400i,700&amp;subset=latin-ext" rel="stylesheet">		
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
@include('g3n1us_editor::head_assets')	
	
</head>
<body style="-webkit-overflow-scrolling: touch;">
	<x-toolbar :page="$page"/>	
	<section id="main" class="pt-3">
		<div style="max-width: 800px; margin: auto; position: relative;" class="alerts">
			<div class="alert alert-info">{!!session('info')!!}</div>
			<div class="alert alert-danger">{!!session('error')!!}</div>
			<div class="alert alert-success">{!!session('message')!!}</div>
		</div>
		
@yield('content')

	</section>	
	<footer>
		
	</footer>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
	@include('g3n1us_editor::foot_assets')


@yield('g3n1us_editor::footer')

</body>
</html>

