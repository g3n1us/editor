		<link rel="icon" href="/images/bookmark-icons/favicon.ico">
		<link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
		
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script>
			window.formErrors = {!! $errors->toJSON() !!};
		    window.csrf_token = '{{ csrf_token() }}';
		    var is_editing_homepage = {{request()->edit_mode ?? 'false'}};
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
