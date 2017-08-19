@extends('layouts.master')
@section('title')
Purchase Order
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">	
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
			<legend><h3 class="text-muted">Purchase Order</h3></legend>
			<table class="table table-hover table-striped table-bordered table-condensed" id="purchaseOrderTable">
				<thead>
					<th>P.O. Number / APR</th>
					<th>Date</th>
					<th>Fund Cluster</th>
					<th>Details</th>
					@if(Auth::user()->accesslevel == 1)
					<th class="col-md-2"></th>
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

	    var table = $('#purchaseOrderTable').DataTable({
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
			ajax: "{{ url('purchaseorder') }}",
			columns: [
					{ data: "purchaseorderno" },
					{ data: "date" },
					{ data: "fundcluster" },
					{ data: "details" }
					@if(Auth::user()->accesslevel == 1)
					,{ data: function(callback){
						url = '{{ url("purchaseorder") }}' + '/' + callback.purchaseorderno
						return `
							<a type='button' href='` + url + `' class='btn btn-default btn-sm'>View</a>
							<button type='button' data-id='` + url + `' data-fundcluster='` + callback.fundcluster + `' class='fundcluster btn btn-info btn-sm'>Set Fund Cluster</button>
							`
					} }
					@endif
			],
	    });

	 	$("div.toolbar").html(`
			<button id="create" class="btn btn-md btn-primary">
				<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
				<span id="nav-text"> Create</span>
			</button>
		`);

		$('#create').on('click',function(){
			window.location.href = "{{ url('purchaseorder/create') }}"
		})

	    $('#purchaseOrderTable').on('click','.fundcluster',function(){
	    	url = "";
	    	id = $(this).data('id')
	    	fundcluster = $(this).data('fundcluster')
	    	swal({
			  title: "Input Fund Cluster!",
			  text: "If multiple, comma separate each fund cluster:",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputValue: fundcluster
			},
			function(inputValue){
			  if (inputValue === false) return false;
			  
			  if (inputValue === "") {
			    swal.showInputError("You need to write something!");
			    return false
			  }
			  
			  $.ajax({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			  	type: 'put',
			  	url: id,
			  	dataType: 'json',
			  	data: {
			  		'fundcluster': inputValue
			  	},
			  	success: function(response){
			  		if(response == 'success')
			  		swal('Success','Operation Successful','success')	
			  		else
			  		swal('Error','Problem Occurred while processing your data','error')
			  		table.ajax.reload();
			  	},
			  	error: function(){
			  		swal('Error','Problem Occurred while processing your data','error')
			  	}
			  })
			});
	    })

		$('#page-body').show();
	} );
</script>
@stop
	