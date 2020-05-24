@extends('pages._parent')

@section('head')
<title>{{$page->title}} | Arlington Science Focus School PTA</title>
@endsection

@section('content')
<div class="container mt-4">
<div class="row">
	<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3" id="paymentFormOuter">

{!! $page->content !!}
@php
$user = auth()->user() ?: new \App\User;
$person = $user->person;
@endphp		
		<div class="card card-block mb-4 " style="box-shadow: none;">
		<form action="#" data-action="/payment-process" method="POST" class="donation_form">
			<fieldset class="form-group">
				<label class="form-label d-block" for="amount"><b>Donation Amount</b></label>				
				<div class="input-group input-group-lg">
					<span class="input-group-prepend"><i class="fa fa-dollar input-group-text"></i></span>					
					<input type="tel" required pattern="[0-9]*" name="amount" id="amount" data-denomination="dollar" autofocus class="form-control text-right">
					<span class="input-group-prepend"><b class="input-group-text">.00</b></span>					
				</div>
			</fieldset>
			
			
			<div style="display: none">
				<input type="hidden" name="description" value="No Frills Fundraiser Donation 2018-2019 School Year">
				<input type="hidden" name="metadata[sku]" value="no_frills_fundraiser_2018">
				<input type="hidden" name="items[0][sku]" value="no_frills_fundraiser_2018">
				<input type="hidden" name="items[0][description]" value="No Frills Fundraiser Donation 2018-2019 School Year">
			</div>
			<button class="btn btn-primary mb-3" type="submit"><i class="fa fa-cart-plus"></i> Donate</button>
			
		</form>
		<script>
			(function(){
				var el = document.getElementById('amount');
				var formGroup = el.parentNode.parentNode;
				var currentVal = null;
				payform = document.getElementById('payform');
				el.addEventListener('input', function(e){
					var isok = el.checkValidity();
					if(!isok) {
						formGroup.classList.add('has-danger');
						setTimeout(function(){
							formGroup.classList.remove('has-danger');
						}, 100);
						el.value = currentVal;
						return;
					}
					formGroup.classList.remove('has-danger');						
					currentVal = el.value;
				});
			})()
		</script>
		
		
		</div>

	</div>
</div>
</div>


@endsection
