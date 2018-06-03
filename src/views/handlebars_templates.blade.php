@verbatim

<script id="template--family" type="text/template">
	<div class="card">
		<div class="card-header"> </div>
		<div class="card-block">
			{{#each this.members as |person|}}
			<h6 class="mb-1"><span class="badge badge-default">{{#if is_student}}student{{else}}parent{{/if}}</span></h6>
			<div class="columns">
			<p class="card-text mb-1"><b><a title="view in directory" href="/dashboard/directory?query={{person.first_name}} {{person.last_name}}">{{person.first_name}} {{person.last_name}}</a></b></p>
			<p class="card-text mb-1"><a rel="tooltip" title="Click to email" target="_blank" href="mailto:{{person.email}}">{{person.email}}</a></p>
			<p class="card-text mb-1"><a rel="tooltip" title="Click to call(if supported)" href="tel:{{person.tidy_phone}}">{{person.phone}}</a></p>
			<p class="card-text mb-1">{{person.address}} {{person.city}}</p>
			</div>
			<hr>
			{{/each}}
		</div>
	</div>
</script>


<script id="template--card" type="text/template" title="A Bootstrap Card" data-required_class="card-widget" data-editables='[".card-header", ".card-block"]'>
	<div class="card card-widget"><div class="card-header"><h4>Title</h4></div><div class="card-block"><p>More text here</p></div></div>
</script>


<script id="template--card_img_top" type="text/template" title="A Bootstrap Card with Top Image" data-required_class="card-img-top-widget" data-editables='[".card-img-top-wrapper", ".card-block"]'>
<div class="card card-img-top-widget"><div class="card-img-top-wrapper"><img class="card-img-top align-self-center" src="http://lorempixel.com/800/800/nature/"></div><div class="card-block card-body"><p>More text here</p></div></div>
</script>
@endverbatim

@php
$sizes = ['btn-sm', '', 'btn-lg'];
$sizelabels = ['Small', 'Medium', 'Large'];
@endphp

@foreach($sizes as $sizeindex => $size)
<script id="template--button_red" type="text/template" title="{{$sizelabels[$sizeindex]}} Red Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-danger {{$size}}" href="https://google.com">Button</a>
</script>
<script id="template--button_red"_outline type="text/template" title="{{$sizelabels[$sizeindex]}} Red Outline Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-outline-danger {{$size}}" href="https://google.com">Button</a>
</script>
@endforeach

@foreach($sizes as $sizeindex => $size)
<script id="template--button_green" type="text/template" title="{{$sizelabels[$sizeindex]}} Green Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-success {{$size}}" href="https://google.com">Button</a>
</script>
<script id="template--button_green_outline" type="text/template" title="{{$sizelabels[$sizeindex]}} Green Outline Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-outline-success {{$size}}" href="https://google.com">Button</a>
</script>
@endforeach


@foreach($sizes as $sizeindex => $size)
<script id="template--button_yellow" type="text/template" title="{{$sizelabels[$sizeindex]}} Yellow Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-warning {{$size}}" href="https://google.com">Button</a>
</script>
<script id="template--button_yellow_outline" type="text/template" title="{{$sizelabels[$sizeindex]}} Yellow Outline Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-outline-warning {{$size}}" href="https://google.com">Button</a>
</script>
@endforeach

@foreach($sizes as $sizeindex => $size)
<script id="template--button_blue" type="text/template" title="{{$sizelabels[$sizeindex]}} Blue Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-info {{$size}}" href="https://google.com">Button</a>
</script>
<script id="template--button_blue_outline" type="text/template" title="{{$sizelabels[$sizeindex]}} Blue Outline Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-outline-info {{$size}}" href="https://google.com">Button</a>
</script>
@endforeach

@foreach($sizes as $sizeindex => $size)
<script id="template--button_purple" type="text/template" title="{{$sizelabels[$sizeindex]}} Purple Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-primary {{$size}}" href="https://google.com">Button</a>
</script>
<script id="template--button_purple_outline" type="text/template" title="{{$sizelabels[$sizeindex]}} Purple Outline Button" data-required_class="btn" data-editables='[".btn"]'>
	<a class="btn btn-outline-primary {{$size}}" href="https://google.com">Button</a>
</script>
@endforeach


