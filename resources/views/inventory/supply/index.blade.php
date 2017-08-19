@extends('layouts.master')
@section('title')
Supply Inventory
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
			<legend><h3 class="text-muted">Supplies Inventory</h3></legend>
			<table class="table table-hover table-striped table-bordered table-condensed" id="supplyInventoryTable">
				<thead>
					<th>Stock No.</th>
					<th>Supply Item</th>
					<th>Unit</th>
					<th>Reorder Point</th>
					@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
					<th></th>
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

	    var table = $('#supplyInventoryTable').DataTable({
			select: {
				style: 'single'
			},
			language: {
					searchPlaceholder: "Search..."
			},
			@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
			"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			@endif
			"processing": true,
			ajax: "{{ url('maintenance/supply') }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "supplytype" },
					{ data: "unit" },
					{ data: "reorderpoint" },
					@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
		            { data: function(callback){
		            	return `
		            			@if(Auth::user()->accesslevel == 0)
		            			<a href="{{ url("inventory/supply") }}` + '/' + callback.stocknumber  + '/stockcard' +`" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-list"></span> Stockcard</a>
		            			@endif
		            			@if(Auth::user()->accesslevel == 1)
		            			<a href="{{ url("inventory/supply") }}` + '/' + callback.stocknumber  + '/supplyledger' +`" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-list"></span> Supply Ledger</a>
		            			@endif
		            	`;
		            } }
		            @endif
			],
	    });

	    @if(Auth::user()->accesslevel == 1)
	 	$("div.toolbar").html(`
				<button id="accept" class="btn btn-sm btn-success">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					<span id="nav-text"> Batch Accept</span>
				</button>
				<button id="release" class="btn btn-sm btn-danger">
					<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
					<span id="nav-text"> Batch Release</span>
				</button>
		`);
		@endif

		@if(Auth::user()->accesslevel == 0)
	 	$("div.toolbar").html(`
			<button id="accept" class="btn btn-sm btn-success">
				<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span id="nav-text"> Batch Accept</span>
			</button>
			<button id="release" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span id="nav-text"> Batch Release</span>
			</button>
		`);
		@endif

		$('#accept').on("click",function(){
			@if(Auth::user()->accesslevel == 0)
			window.location.href = "{{ url('inventory/supply/stockcard/batch/form/accept') }}"
			@elseif(Auth::user()->accesslevel == 1)
			window.location.href = "{{ url('inventory/supply/supplyledger/batch/form/accept') }}"
			@endif
		});

		$('#release').on('click',function(){
			@if(Auth::user()->accesslevel == 0)
			window.location.href = "{{ url('inventory/supply/stockcard/batch/form/release') }}"
			@elseif(Auth::user()->accesslevel == 1)
			window.location.href = "{{ url('inventory/supply/supplyledger/batch/form/release') }}"
			@endif

		});

		$('#page-body').show();
	} );
</script>
@stop
	