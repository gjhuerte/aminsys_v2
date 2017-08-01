@extends('layouts.master')
@section('title')
Office
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"  />
<style>
	#page-body{
		display:none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="office-info">
		<div class="col-sm-12 panel panel-body table-responsive">
			<table class="table table-striped table-hover table-bordered" id='officeTable'>
				<thead>
					<th>Department Code</th>
					<th>Department Name</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function(){

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif


	    var table = $('#officeTable').DataTable( {
			"pageLength": 100,
	  		select: {
	  			style: 'single'
	  		},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('maintenance/office') }}",
	        columns: [
	            { data: "deptcode" },
	            { data: "deptname" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("maintenance/office") }}` + '/' + callback.deptcode + '/edit' + `" class="btn btn-sm btn-default">Edit</a>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('maintenance/office/create') }}" id="new" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Add
 			</a>
		`);

		$('#officeTable').on('click','button.remove',function(){
			console.log('clicked')
			$.ajax({
				type: 'delete',
				url: '{{ url("maintenance/office") }}' + '/' + $(this).data('id'),
				dataType: 'json',
				success: function(response){
					swal("Operation Success",'An office has been removed.',"success")
				}, 
				error: function(response){
					swal("Error Occurred",'An error has occurred while processing your data.',"error")
				}

			})
		})

		$('#page-body').show();

	});
</script>
@stop
