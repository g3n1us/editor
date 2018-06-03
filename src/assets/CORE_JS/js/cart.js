function Cart(){
	var _this = this;
	_this.amount = 0;
// 	_this.description = null;
	_this.handlers_set = false;
	_this.formSelector = '#payform';
	_this.rootSelector = '#paymentFormOuter';
	_this.stripe_public_key = window.public_stripe_api_key;
	_this.cart = [];
	
	_this.init = function(){
		_this.setHandlers();
		_this.cart = _this.getCart();
	}

	_this.watch('cart', function(prop,oldval,cart){
		_this.amount = 0;
		
		for(var i in cart){
			_this.amount += parseInt(cart[i].amount);
		}
		
		if(cart.length)
			$('.shopping-cart, .checkout').show();
		else
			$('.shopping-cart, .checkout').hide();
	});
		
	_this.getCart = function(){
		// item = {description: string, amount: number, sku: string|unique}
		var cart = JSON.parse(localStorage.cart || '[]');
// 		console.log(cart);
// 		console.log(_this.amount);
		_this.cart = cart;
		return cart;
	}		

	
	_this.addToCart = function(item){
		var cart = _this.getCart();
		cart.push(item);
		localStorage.cart = JSON.stringify(cart);
	}


	_this.showCart = function(){
		$(_this.formSelector).find('.not-cart').hide();
		var cart = _this.getCart();
			
		var total_string = '$' + _this.amount/100;
		if(_this.amount >= 100) total_string += '.00';
		$('.cart').html(handlebars_templates.checkout({
			items: cart, 
			total: _this.amount, 
			total_string: total_string,
		}));
	}
	
	
	_this.removeItem = function(item){
		var cart = _this.getCart();
		cart.splice(item,1);		
// 		delete cart[item];
		localStorage.cart = JSON.stringify(cart);
		_this.getCart();
		_this.showCart();
	}
	
	
	_this.handler = StripeCheckout.configure({
		key: _this.stripe_public_key,
		image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		locale: 'auto',
		allowRememberMe: false,
// 		email: current_user_email,
// 		billingAddress: true,
		zipCode: true,
		
		token: function(token, args) {
			var formdata = form2Object($(_this.formSelector), token);
			formdata.amount = _this.amount;
			formdata.items = _this.getCart();
			var request = $.ajax({
				url: "/payment-process",
				type: "POST",
				data: formdata,
			});

			$(_this.rootSelector).html(handlebars_templates.checkout_processing());

			request.done(function( data ) {
				console.log(data);
				if(data.status === "succeeded"){
					$(_this.rootSelector).html(handlebars_templates.checkout_thanks());
					localStorage.cart = '[]';
					_this.getCart();
				}
				else{
					$(_this.rootSelector).html(handlebars_templates.checkout_error());
					_this.showCart();					
				}
			});
			
			request.fail(function( data, jqXHR, textStatus ) {
				console.log(data);
				alert( "Request failed: " + textStatus );
			});	
		}
	});
	
	
	_this.setHandlers = function(){
		if(_this.handlers_set)
			return;
			
		$(document).on('click', '.checkout', function(e) {
			if(_this.amount === 0) return false;
			var config = {
				name: 'ASFS PTA',
				description: '',
				amount: _this.amount,
			};
			if(current_user_email)
				config.email = current_user_email;
			else
				config.billingAddress = true;
			
			_this.handler.open(config);
				
			e.preventDefault();
		});
		
		$(_this.formSelector).on('submit', function(e){
			return false;
		});
		
		$(document).on('click', '.shopping-cart', _this.showCart);
		
		// Close Checkout on page navigation:
		window.addEventListener('popstate', function() {
		  handler.close();
		});
		
		

		$(document).on('click', '#addCart, .add_cart', function(e){
			var $item = $('[name="item"]');
			var update_hidden_amount = false;
			if($item.is('[type="radio"]')){
				update_hidden_amount = true;
				$item = $('[name="item"]:checked');
			}
				
// 			var amount = $item.data('amount') || ($item.val().indexOf('individual') !== -1) ? 1000 : 2000;
			var amount = $item.data('amount') || $('[name="amount"]').val();
			description = $item.parent().text().trim();
			if(description.indexOf('[[amount]]') !== -1) 
				description = description.replace('[[amount]]', amount.toString());
			if($('[name="amount"]').data('denomination') == 'dollar')
				amount = amount * 100;
			
			console.log({description: description, amount: amount, sku: $item[0].value});
			_this.addToCart({description: description, amount: amount, sku: $item[0].value});
			_this.showCart();
			if(update_hidden_amount)
				document.getElementById('amount').value = _this.amount;
		});
		
		
		_this.handlers_set = true;		
	} //setHandlers
	
}

var MyCart = new Cart();
if($(MyCart.formSelector).length)
	MyCart.init();
