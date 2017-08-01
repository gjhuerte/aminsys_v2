@extends('layouts.master')
@section('title')
Audit Trail
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
			<table class="table table-hover table-striped table-bordered table-condensed" id="supplyInventoryTable">
				<thead>
					<th>Date</th>
					<th>Acitivity Done</th>
					<th>Column Involved</th>
					<th>Old Value</th>
					<th>New Value</th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    var table = $('#supplyInventoryTable').DataTable({
			select: {
				style: 'single'
			},
			language: {
					searchPlaceholder: "Search..."
			},
			@if(Auth::user()->accesslevel == 0)
			"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			@endif
			"processing": true,
			ajax: "{{ url('audittrail') }}",
			columns: [
					{ data: function(callback){
						return moment(callback.date).format('LLLL')
					}},
					{ data: "status" },
					{ data: "columnname" },
					{ data: "oldvalue" },
					{ data: "newvalue" }
			],
	    });

	 // 	$("div.toolbar").html(`
		// 		<button id="accept" class="btn btn-sm btn-success">
		// 			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
		// 			<span id="nav-text"> Batch Accept</span>
		// 		</button>
		// 		<button id="release" class="btn btn-sm btn-danger">
		// 			<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
		// 			<span id="nav-text"> Batch Release</span>
		// 		</button>
		// `);
		@if(Auth::user()->accesslevel == 0)
	 	$("div.toolbar").html(`
			<button id="release" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
				<span id="nav-text"> Batch Release</span>
			</button>
		`);
		@endif

		$('#accept').on("click",function(){
			@if(Auth::user()->accesslevel == 0)
			window.location.href = "{{ url('inventory/supply/stockcard/batch/form/accept') }}"
			@endif
		});

		$('#release').on('click',function(){
			@if(Auth::user()->accesslevel == 0)
			window.location.href = "{{ url('inventory/supply/stockcard/batch/form/release') }}"
			@endif

		});

		$('#page-body').show();
	} );
</script>
@stop
	