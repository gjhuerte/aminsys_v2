@extends('layouts.master')
@section('title')
Supply Ledger
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/buttons.bootstrap.min.css')) }}
{{ HTML::style(asset('css/buttons.dataTables.min.css')) }}
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
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
	<div class="panel panel-default">
		<div class="panel-body table-responsive">	
			<legend><h3 class="text-muted">Supply Ledger Summary</h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory/supply') }}">Inventory</a></li>
				<li class="active">{{ $supply->stocknumber }}</li>
				<li class="active">Supply Ledger</li>
				<li class="active">Summary</li>
			</ul>
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable" cellspacing="0" width="100%">
				<thead>
		            <tr rowspan="2">
		                <th class="text-left" colspan="7">Entity Name:  <span style="font-weight:normal">{{ $supply->entityname }}</span> </th>
		                <th class="text-left" colspan="7"></span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="7">Item:  <span style="font-weight:normal">{{ $supply->supplytype }}</span> </th>
		                <th class="text-left" colspan="7">Stock No.:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="7">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit }}</span>  </th>
		                <th class="text-left" colspan="7">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-center" colspan="2"></th>
		                <th class="text-center" colspan="3">Receipt</th>
		                <th class="text-center" colspan="3">Issue</th>
		                <th class="text-center" colspan="3">Balance</th>
		                <th class="text-center" colspan="2"></th>
		            </tr>
					<tr>
						<th>Date</th>
						<th>Reference</th>
						<th>Qty</th>
						<th>Unit Cost</th>
						<th>Total Cost</th>
						<th>Qty</th>
						<th>Unit Cost</th>
						<th>Total Cost</th>
						<th>Qty</th>
						<th>Unit Cost</th>
						<th>Total Cost</th>
						<th>Days To Consume</th>
						<th class="no-sort"></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/dataTables.buttons.min.js')) }}
{{ HTML::script(asset('js/buttons.html5.min.js')) }}
{{ HTML::script(asset('js/buttons.print.min.js')) }}
{{ HTML::script(asset('js/jszip.min.js')) }}
{{ HTML::script(asset('js/pdfmake.min.js')) }}
{{ HTML::script(asset('js/vfs_fonts.js')) }}
{{ HTML::script(asset('js/buttons.bootstrap.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		var quantity = 0;
		var unitcost = 0;
		var totalcost = 0;

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

	    var table = $('#inventoryTable').DataTable({
			"pageLength": 50,
			select: {
				style: 'single'
			},
        	lengthChange: false,
        	buttons: [ 
        					'excel', 
        					{
	    						extend: 'print',
	    						title: '*',
	    						messageBottom: "*** Nothing Follows ***",
                				message: function(){
                					return `
								                <th class="text-left" colspan="4">Entity Name:  <span style="font-weight:normal">{{ $supply->entityname }}</span> </th>
									            <br />
								                <th class="text-left" colspan="4">Fund Cluster:  <span style="font-weight:normal">{{ $supply->fundcluster }}</span> </th>
								                <br />
								                <th class="text-left" colspan="4">Item:  <span style="font-weight:normal">{{ $supply->supplytype }}</span> </th>
								                <th class="text-left" colspan="4">Stock No.:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
									            <br />
								                <th class="text-left" colspan="4">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit }}</span>  </th>
								  		          <br />
								                <th class="text-left" colspan="4">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
				                            `;
                				},

        					}
    				 ],
			initComplete : function () {
     			table.buttons().container()
        			.appendTo( 'div.print' );			
    		},
			"columnDefs":[
				{ "type": "date", "targets": 0 },
				{ targets: 'no-sort', orderable: false }
			],
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-2'B<'print'>><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: '{{ url("inventory/supply/$supply->stocknumber/supplyledger/") }}',
			columns: [
					{ data: "date" },
					{ data: function(){
						return ""
					} },
					{ data: "receiptquantity"},
					{ data: function(callback){
						try{
							return parseInt(callback.receiptunitprice).toFixed(2)
						} catch(e) { quantity = 0; return null }
					} },
					{ data: function(callback){
						try {
							return (callback.receiptquantity * callback.receiptunitprice).toFixed(2);
						} catch (e) { return null }
					} },
					{ data: "issuequantity" },
					{ data: function(callback){
						try{
							return parseInt(callback.issueunitprice)
						} catch(e) { quantity = 0; return null }
					} },
					{ data: function(callback){
						try {
							return (callback.issuequantity * callback.issueunitprice).toFixed(2);
						} catch (e) { return null } 
					} },
					{ data: function(callback){
						try{
							quantity = quantity + (callback.receiptquantity - callback.issuequantity)
							return quantity.toFixed(2)
						} catch(e) { quantity = 0; return null }
					} },
					{ data: function(callback){
						try{
							unitcost = ((callback.receiptquantity * callback.receiptunitprice) - (callback.issuequantity * callback.issueunitprice)) / quantity
							return unitcost.toFixed(2)
						} catch(e) { unitcost = 0; return null }
					} },
					{ data: function(callback){
						try{
							return (quantity * unitcost).toFixed(2);
						} catch (e) { return null }
					} },
					{ data: function(){
						return ""
					} },
					{ data: function(callback){
						url = '{{ url("inventory/supply/$supply->stocknumber/supplyledger") }}' + '/' + callback.date 
						return "<a type='button' href='" + url + "' class='btn btn-default btn-sm'>View</a>"
					} },
			],
	    });

	 	$("div.toolbar").html(`
			<button id="accept" class="btn btn-sm btn-success">
				<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
				<span id="nav-text"> Accept</span>
			</button>
			<button id="release" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
				<span id="nav-text"> Release</span>
			</button>
		`);

		$('#accept').on('click',function(){
			window.location.href = "{{ url('inventory/supply') }}" + '/' + "{{ $supply->stocknumber }}" + '/supplyledger/create'
		});

		$('#release').on('click',function(){
			window.location.href = "{{ url('inventory/supply') }}" + '/' + "{{ $supply->stocknumber }}" + '/supplyledger/release'
		});

		$('#page-body').show();
	} );
</script>
@stop
	