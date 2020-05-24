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
		<form action="#" data-action="/payment-process" method="POST" class="" id="payform">
			{{ csrf_field() }}
			<fieldset class="form-group">
				<label class="form-label d-block" for="amount"><b>Donation Amount</b></label>				
				<div class="input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-dollar"></i></span>					
					<input type="tel" required pattern="[0-9]*" name="amount" id="amount" data-denomination="dollar" value="50" autofocus class="form-control text-right">
					<span class="input-group-addon"><b>.00</b></span>					
				</div>
			</fieldset>
			
			
			<div style="display: none">
				5th Grade Promotion Donation : $[[amount]].00
				<input type="hidden" name="item" value="fifth_grade_promotion_2017">
			</div>
			<button class="btn btn-primary mb-3" id="addCart" type="button"><i class="fa fa-cart-plus"></i> Add to Cart</button>
			
			<div class="cart"></div>
		</form>
		<script>
			(function(){
				var el = document.getElementById('amount');
				if(!'checkValidity' in el)
					return;
					
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
					console.log(e);
				});
			})()
		</script>
		
		
		</div>

	</div>
</div>
</div>


@endsection
