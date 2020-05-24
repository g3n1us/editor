
<!-- Modal -->
<div class="modal fade" id="deletion_modal_{{$person->id}}" tabindex="-1" role="dialog" aria-labelledby="deletion_modal_{{$person->id}}" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" action="{{route('directory.destroy', $person->id, false)}}" method="post">
			<div class="modal-header">
			<h4 class="modal-title">Delete Person</h4>
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			
			</div>
			<div class="modal-body text-danger">
				@php
				$delete_code = str_random(10);
				@endphp
				<heads-up style="vertical-align: bottom"></heads-up> To permanently delete this person from the system, type the code <pre class="my-2" style="user-select:none; ">{{$delete_code}}</pre> into the box below, then click the DELETE button below. This action cannot be reversed.
				
				<input type="text" class="form-control my-4" required pattern="{{$delete_code}}" name="safety-text">
			</div>
			<div class="modal-footer">
				{{csrf_field()}}
				{{method_field('delete')}}
			<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-danger">DELETE</button>
			</div>
		</form><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
