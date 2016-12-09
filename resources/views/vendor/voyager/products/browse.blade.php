@extends('voyager::master')


@section('page_header')
	<h1 class="page-title">
		<i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }} <a
				href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success"><i class="voyager-plus"></i> Add
			New</a>
	</h1>
@stop

@section('page_header_actions')

@stop

@section('content')

	<div class="page-content container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-bordered">
					<div class="panel-body">
					<table id="dataTable" class="table table-hover">
					    <thead>
					      <tr>
							  <th>Product Name</th>
							  <th>SKU</th>
							  <th>Created</th>
							  <th>Price</th>
							  <th>Sale Price</th>
							  <th>Available</th>
							  <th>Active</th>
					      	<th class="actions">Actions</th>
					      </tr>
					    </thead>
					    <tbody>
							@foreach($variations as $data)
							<tr>
								<td>
									<div class="popover-wrapper" data-placement="right" data-img="{{ $data->product->main_image }}">{{ $data->product->name }} - {{$data->size}}</div>
								</td>
								<td>{{$data->sku}}</td>
								<td>{{$data->created_at->format('d M Y')}}</td>
								<td>{{$data->price}}</td>
								<td>{{($data->sale_price) ?: '-'}}</td>
								<td>{{$data->available}}</td>
								<?php $checked = ($data->product->active == 1) ? true : false; ?>
								<td><input type="checkbox" name="active" data-id="{{$data->product->id}}" class="toggleswitch toggleactive" @if($checked) checked @endif></td>
								<td class="no-sort no-click">
									<div class="dropdown">
										<a id="dLabel" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Edit
											<a class="caret"></a>
										</a>
										<ul class="dropdown-menu" aria-labelledby="dLabel">
											<li>
												<a href="{{ route('voyager.'.$dataType->slug.'.edit', $data->product->id) }}"
												   class="edit">Edit Product</a>
											</li>
											<li role="separator" class="divider"></li>
											<li>
												<a class="delete" data-id="{{$data->product->id}}"
													 id="delete-{{$data->product->id}}">Delete
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							@endforeach
					    </tbody>
					</table>
					</div>
				</div>
			</div>
	    </div>
	</div>
	</div>
	<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete
						this {{ $dataType->display_name_singular }}?</h4>
				</div>
				<div class="modal-footer">
					<form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
						<input type="hidden" name="_method" value="DELETE">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="submit" class="btn btn-danger pull-right delete-confirm"
							   value="Yes, Delete This {{ $dataType->display_name_singular }}">
					</form>
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop

@section('javascript')
	<!-- DataTables -->
    <script>

      $(document).ready(function(){
        $('#dataTable').DataTable({ "order": [] });
	  	$('.toggleswitch').bootstrapToggle();
	    $('.popover-wrapper').popover({
			html: true,
			content: function () {
				return '<img src="'+$(this).data('img') + '" height="200" />';
			},
			trigger: 'hover'
		});
      });

	  $('td').on('click', '.delete', function (e) {
		  var id = $(this).data('id');
		  var form = $('#delete_form')[0];
		  var action = parseActionUrl(form.action, id);

		  form.action = action;

		  $('#delete_modal').modal('show');

	  });

	  function parseActionUrl(action, id) {
		  if (action.match(/\/[0-9]+$/)) {
			  return action.replace(/([0-9]+$)/, id);
		  }
		  return action + '/' + id;
	  }

	  $('.toggleactive').on('change', function() {
		  $.post('/admin/products/' + $(this).data('id') + '/active', { active: ($(this).is(':checked')) ? 1 : 0, _token : '{{ csrf_token() }}' }, function(){
			  toastr.success("Successfully updated product active.");
		  });
	  });


    </script>
@stop
