@extends('layouts.master')
@section('title')
Stock Card
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
			<legend><h3 class="text-muted">Stock Card</h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory/supply') }}">Inventory</a></li>
				<li class="active">{{ $supply->stocknumber }}</li>
				<li class="active">Stock Card</li>
			</ul>
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable" cellspacing="0" width="100%">
				<thead>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Entity Name:  <span style="font-weight:normal">{{ $supply->entityname }}</span> </th>
		                <th class="text-left" colspan="4">Fund Cluster:  <span style="font-weight:normal">{{ $supply->fundcluster }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Item:  <span style="font-weight:normal">{{ $supply->supplytype }}</span> </th>
		                <th class="text-left" colspan="4">Stock No.:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
		            </tr>
		            <tr rowspan="2">
		                <th class="text-left" colspan="4">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit }}</span>  </th>
		                <th class="text-left" colspan="4">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
		            </tr>
					<tr>
						<th>Date</th>
						<th>Reference</th>
						<th>Receipt Qty</th>
						<th>Issue Qty</th>
						<th>Office</th>
						<th>Balance Qty</th>
						<th>Days To Consume</th>
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
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

		var balance = 0;

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
				                // customize: function ( win ) {
				                //     $(win.document.body)
				                //         .css( 'font-size', '10pt' )
				                //         .prepend(
				                //             `
									           //  <tr rowspan="2">
									           //      <th class="text-left" colspan="4">Entity Name:  <span style="font-weight:normal">{{ $supply->entityname }}</span> </th>
									           //      <th class="text-left" colspan="4">Fund Cluster:  <span style="font-weight:normal">{{ $supply->fundcluster }}</span> </th>
									           //  </tr>
									           //  <tr rowspan="2">
									           //      <th class="text-left" colspan="4">Item:  <span style="font-weight:normal">{{ $supply->supplytype }}</span> </th>
									           //      <th class="text-left" colspan="4">Stock No.:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
									           //  </tr>
									           //  <tr rowspan="2">
									           //      <th class="text-left" colspan="4">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit }}</span>  </th>
									           //      <th class="text-left" colspan="4">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
									           //  </tr>
				                //             `
				                //         );
				 
				                //     $(win.document.body).find( 'table' )
				                //         .addClass( 'compact' )
				                //         .css( 'font-size', 'inherit' );
				                // }

        					}
    				 ],
			initComplete : function () {
     			table.buttons().container()
        			.appendTo( 'div.print' );			
    		},
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-2'<'print'>><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"columnDefs":[
				{ "type": "date", "targets": 0 },
			],
			"processing": true,
			ajax: '{{ url("inventory/supply/$supply->stocknumber/stockcard/") }}',
			columns: [
					{ data: function(callback){
						return moment(callback.date).format("MMM d YYYY")
					} },
					{ data: "reference" },
					{ data: function(callback){
						if(callback.receiptquantity == null)
							return 0
						else 
							return callback.receiptquantity
					}},
					{ data: function(callback){
						if(callback.issuequantity == null)
							return 0
						else 
							return callback.issuequantity
					}},
					{ data: function(callback){
						if(callback.office == null || callback.office == "")
							return "N/A"
						else 
							return callback.office
					}},
					{ data: function(callback){
						if(callback.receiptquantity != null)
						{
							balance = balance + callback.receiptquantity
							return balance.toString()
						} else
						{
							balance = balance - callback.issuequantity
							return balance.toString()
						}
					} },
					{ data: function(callback){
						if(callback.daystoconsume == null || callback.daystoconsume == "")
							return "N/A"
						else 
							return callback.daystoconsume
					}},
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
			window.location.href = "{{ url('inventory/supply') }}" + '/' + "{{ $supply->stocknumber }}" + '/stockcard/create'
		});

		$('#release').on('click',function(){
			window.location.href = "{{ url('inventory/supply') }}" + '/' + "{{ $supply->stocknumber }}" + '/stockcard/release'
		});

		$('#page-body').show();
	} );
</script>
@stop
	