@extends('g3n1us_editor::dashboard.layout')

@section('content')

<div class="container mb-5">
	<div class="row">
		<div class="col-md-12">
			<a class="btn btn-sm btn-outline-primary" href="{{route(session('returnTo', 'directory'))}}"><i class="fa fa-arrow-left"></i> return to {{session('returnTo', 'directory')}}</a>
		</div>
	</div>
	
    <div class="justify-content-center d-flex">
        <div style="max-width: 800px">
<!-- 		        <p class=" mt-3 mb-1">You can change the way your information appears in the directory below. You can also choose to exclude some or all information from appearing in the directory. </p> -->
		        
<!--
		        <div class="card card-block card-outline-danger mb-2" style="box-shadow: none">
			        <span class="text-danger"><heads-up style="vertical-align: bottom"></heads-up> Changing your information here this will NOT change your information with the school system. This must be changed in <a href="http://access.apsva.us/parents/" target="_blank">ParentVUE.</a> Changes made there will be reflected here after the next periodic update to our data.</span>
		        </div>
-->
	        
		        <form method="post" class="" action="{{route('directory_edit')}}" id="directory_edit_form">
			        {{csrf_field()}}
				@foreach($people as $person)
					<div class="card card-block mb-5">
@component('g3n1us_editor::components.person_edit', ['person' => $person]) @endcomponent
					</div>
				@endforeach

					<div class="text-right">
						<button type="submit" class="btn btn-lg btn-primary my-4"><i class="fa fa-save"></i> Save</button>
					</div>

		        </form>
	        
        </div>
    </div>
</div>

{{-- Deletion Modals for each user --}}
				@foreach($people as $person)

	@component('g3n1us_editor::components.deletion_modal', ['person' => $person]) @endcomponent

				@endforeach


@endsection
