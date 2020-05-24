@extends('g3n1us_editor::dashboard.layout')

@section('content')
<style>
	.print_outer{
		page-break-inside: avoid;
	}
	.print_outer h5{
	    color: black;
	    background-color: #dadada;
	    padding: 3px;	
	    font-weight: bold;	
	}
	.print_outer , .print_outer  *{
		font-family: Helvetica !important;
	}
	header{
		display: none;
	}
	.kid span{
		margin-left: auto;
	}
	.kid i{
		width: 33%;
		text-align: right;
	}
</style>
@foreach($by_family as $i => $families)

<div class="print_outer" style="column-count: 3; height: 11in; width: 8.5in; overflow: hidden;">

@foreach($families as $family)
	<div style="break-inside: avoid" class="mb-3">
		<h5 class="mb-0">{{$family->surname}}</h5>
	@foreach($family->members as $member)
		@if($member->is_student)
		<div class="d-flex flex-grow kid" style="justify-content: space-between"><b>{{$member->first_name}}</b> <span>{{ordinal($member->grade) ?: 'K'}}</span> <i>{{$member->teachers ? $member->teachers->last_name : ''}}</i> </div>
		@endif
	@endforeach
	@foreach($family->members as $member)
		@if(!$member->is_student)
		<div class="mt-1"><b>{{$member->first_name}} {{$member->last_name}}</b></div>
		<div><span>{{$member->phone}}</span></div>
		<div><span>{{$member->email}}</span></div>
		@endif
	@endforeach
	</div>	
@endforeach


</div>
@endforeach


@endsection





@push('scripts')
	

@endpush
