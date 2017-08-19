@extends('layouts.master')
@section('title')
Supply
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}

	a > hover{
		text-decoration: none;
	}

	th , tbody{
		text-align: center;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12">
		<div class="panel panel-body table-responsive">
			<legend>Supplies</legend>
			<table class="table table-hover table-striped table-bordered table-condensed" id="supplyTable">
				<thead>
					<th>Stock No.</th>
					<th>Entity Name</th>
					<th>Supply Item</th>
					<th>Unit</th>
					<th>Reorder Point</th>
					@if(Auth::user()->accesslevel == 0)
					<th class="no-sort"></th>
					@endif
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function() {

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    var table = $('#supplyTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			@if(Auth::user()->accesslevel == 0)
			"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			@endif
			"processing": true,
			ajax: "{{ url('maintenance/supply') }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "entityname" },
					{ data: "supplytype" },
					{ data: "unit" },
					{ data: "reorderpoint" }
					@if(Auth::user()->accesslevel == 0)
		           , { data: function(callback){
		            	return `
		            			<a href="{{ url("maintenance/supply") }}` + '/' + callback.stocknumber + '/edit' + `" class="btn btn-default btn-sm btn-block">Edit</a>
		            	`;
		            } }
		            @endif
			],
	    });

		@if(Auth::user()->accesslevel == 0)
	 	$("div.toolbar").html(`
				<a href="{{ url('maintenance/supply/create') }}" class="btn btn-sm btn-primary">
					<span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
					<span id="nav-text"> Add new Supply</span>
				</a>
		`);
		@endif

		$('#page-body').show();
	} );
</script>
@stop
	