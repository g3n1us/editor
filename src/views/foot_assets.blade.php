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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
	
	<script src="/vendor/g3n1us_editor/CORE_JS/js/g3n1us_helpers.js?v="></script>
	<script>
	window.csrf_token = '{{csrf_token()}}';
	</script>
@if(auth()->check())
	<script>
		window.current_user_email = '{{ auth()->user()->email }}';
	</script>
@include('g3n1us_editor::handlebars_templates')
	<script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.min.js"></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/js/ckeditor/ckeditor.js?v="></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/js/ckeditor/adapters/jquery.js?v="></script>
@else
	<script>
		window.current_user_email = false;
	</script>
@endif
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/dist/js/public-compiled.js?v="></script>
	
<!--
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/node_modules/moment/moment.js?v="></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/node_modules/fullcalendar/dist/fullcalendar.min.js?v="></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/node_modules/fullcalendar/dist/gcal.js?v="></script>

	
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/node_modules/push.js/push.js?v="></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/node_modules/xlsx/dist/xlsx.full.min.js"></script>	
-->
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/js/jq_plugins.js?v="></script>
	<script src="/vendor/g3n1us_editor/CORE_JS/_assets/theme/js/index.js?v="></script>
	
<script>
	$('#create_page input#title').on('input', function(e){
		$('#create_page input#path').val('/'+str_slug(this.value));
	});
	$(document).on('focus', '#create_page input#path', function(e){
		$('#create_page input#title').off('input');
	});
</script>



