@extends('layouts.master')
@section('title')
Supply Ledger | {{ $month }}
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
</style>
@stop
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<legend><h3 class="text-muted">Supply Ledger as of {{ $month }}</h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory/supply') }}">Supply Inventory</a></li>
				<li><a href="{{ url("inventory/supply/$supply->stocknumber/supplyledger") }}">{{ $supply->stocknumber }}</a></li>
				<li class="active">{{ $month }}</li>
			</ul>
			<table class="table table-hover" id="supplyLedgerTable">
				<thead>
					<th>Reference</th>
					<th>Receipt Quantity</th>
					<th>Receipt Unit Price</th>
					<th>Issue Quantity</th>
					<th>Issue Unit Price</th>	
					<th>Days to Consume</th>		
				</thead>
				<tbody>
				@foreach($supplyledger as $supplyledger)
					<tr>
						<td>{{ $supplyledger->reference }}</td>
						<td>{{ $supplyledger->receiptquantity }}</td>
						<td>{{ $supplyledger->receiptunitprice }}</td>
						<td>{{ $supplyledger->issuequantity }}</td>
						<td>{{ $supplyledger->issueunitprice }}</td>
						<td>{{ $supplyledger->daystoconsume }}</td>
					</tr>
				@endforeach				
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@section('script-include')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/dataTables.buttons.min.js')) }}
{{ HTML::script(asset('js/buttons.html5.min.js')) }}
{{ HTML::script(asset('js/buttons.print.min.js')) }}
{{ HTML::script(asset('js/jszip.min.js')) }}
{{ HTML::script(asset('js/pdfmake.min.js')) }}
{{ HTML::script(asset('js/vfs_fonts.js')) }}
{{ HTML::script(asset('js/buttons.bootstrap.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script>
$('document').ready(function(){

	    var table = $('#supplyLedgerTable').DataTable({
	        dom: 'Bfrtip',
	        buttons: [
				{
					extend: 'print',
					title: '',
					messageBottom: "*** Nothing Follows ***",
    				message: function(){
    					return `
					               <h3 class="text-center"> Supply Ledger </h3>
					               <p class="text-center"> {{ $month }} </p>
	                            `;
    				},

				},
	            'excel'
	        ]
	    });

	@if( Session::has("success-message") )
		swal("Success!","{{ Session::pull('success-message') }}","success");
	@endif

	@if( Session::has("error-message") )
		swal("Oops...","{{ Session::pull('error-message') }}","error");
	@endif

	$('#page-body').show()
})
</script>
@stop
