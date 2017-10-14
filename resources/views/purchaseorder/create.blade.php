@extends('backpack::layout')

@section('after_styles')
    <!-- Ladda Buttons (loading buttons) -->
    <link href="{{ asset('vendor/backpack/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
      #page-two, #page-body{
        display: none;
      }
    </style>

    <!-- Bootstrap -->
    {{ HTML::style(asset('css/jquery-ui.css')) }}
    {{ HTML::style(asset('css/sweetalert.css')) }}
    {{ HTML::style(asset('css/dataTables.bootstrap.min.css')) }}
@endsection

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Purchase Order</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('purchaseorder') }}">Purchase Order</a></li>
			<li class="active">Create</li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box" style="padding:10px;">
    <div class="box-body">
					{{ Form::open(['method'=>'post','route'=>array('purchaseorder.store'),'class'=>'col-sm-offset-3 col-sm-6 form-horizontal','id'=>'purchaseOrderForm']) }}
			        @if (count($errors) > 0)
			            <div class="alert alert-danger alert-dismissible" role="alert">
			            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                <ul style='margin-left: 10px;'>
			                    @foreach ($errors->all() as $error)
			                        <li>{{ $error }}</li>
			                    @endforeach
			                </ul>
			            </div>
			        @endif
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('Date') }}
							{{ Form::text('date',Input::old('date'),[
								'id' => 'date',
								'class' => 'form-control',
								'readonly',
								'style' => 'background-color: white;'
							]) }}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('Purchase Order Number / APR') }}
							{{ Form::text('po',Input::old('po'),[
								'id' => 'purchaseorder',
								'class' => 'form-control'
							]) }}
							<p class="text-danger">This field is required</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('Details (Optional)') }}
							{{ Form::text('details',Input::old('details'),[
								'class' => 'form-control'
							]) }}
						</div>
					</div>
					<div class="col-md-12" hidden>
						<div class="form-group">
							{{ Form::label('Fund Cluster') }}
							{{ Form::text('fundcluster',Input::old('fundcluster'),[
								'id' => 'fundcluster',
								'class' => 'form-control',
								'style' => 'background-color: white;'
							]) }}
							<p class="text-success">This field will be inputted by accounting department</p>
						</div>
					</div>
					<legend></legend>
					<div class="form-group">
						<div class="col-md-12">
						{{ Form::label('stocknumber','Stock Number') }}
						{{ Form::text('stocknumber',null,[
							'id' => 'stocknumber',
							'class' => 'form-control'
						]) }}
						<p class="text-danger">This field is required</p>
						</div>
					</div>
					<input type="hidden" id="supply-item" />
					<div id="stocknumber-details">
					</div>
					<div class="col-md-12">
						<div class="form-group">
						{{ Form::label('Ordered Quantity') }}
						{{ Form::text('quantity',null,[
							'id' => 'quantity',
							'class' => 'form-control'
						]) }}
						<p class="text-danger">This field is required</p>
						</div>
					</div>
					<div style="margin-bottom: 20px;">
						<button type="button" id="add" class="btn btn-md btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
					</div>
					<table class="table table-hover table-condensed table-bordered" id="supplyTable">
						<thead>
							<tr>
								<th>Stock Number</th>
								<th>Information</th>
								<th>Quantity</th>
								<th>Unit Price</th>
								<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" id="accept" class="btn btn-md btn-primary">Accept</button>
						</div>
						<div class="btn-group">
							<button type="button" id="cancel" class="btn btn-md btn-default">Cancel</button>
						</div>
					</div>
					{{ Form::close() }}

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
    <!-- Ladda Buttons (loading buttons) -->
    <script src="{{ asset('vendor/backpack/ladda/spin.js') }}"></script>
    <script src="{{ asset('vendor/backpack/ladda/ladda.js') }}"></script>

    {{ HTML::script(asset('js/jquery-ui.js')) }}
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {{ HTML::script(asset('js/sweetalert.min.js')) }}
    {{ HTML::script(asset('js/jquery.dataTables.min.js')) }}
    {{ HTML::script(asset('js/dataTables.bootstrap.min.js')) }}
		{{ HTML::script(asset('js/moment.min.js')) }}
		<script>
		$('document').ready(function(){

			@if(Auth::user()->accesslevel == 0)
			$('#fundcluster').prop('readonly','readonly')
			@endif

			$('#stocknumber').autocomplete({
				source: "{{ url("get/inventory/supply/stocknumber") }}"
			})

			$('#office').autocomplete({
				source: "{{ url('get/office/code') }}"
			})

			$('#accept').on('click',function(){
				if($('#supplyTable > tbody > tr').length == 0)
				{
					swal('Blank Field Notice!','Supply table must have atleast 1 item','error')
				} else {
		        	swal({
			          title: "Are you sure?",
			          text: "This will no longer be editable once submitted. Do you want to continue?",
			          type: "warning",
			          showCancelButton: true,
			          confirmButtonText: "Yes, submit it!",
			          cancelButtonText: "No, cancel it!",
			          closeOnConfirm: false,
			          closeOnCancel: false
			        },
			        function(isConfirm){
			          if (isConfirm) {
			            $('#purchaseOrderForm').submit();
			          } else {
			            swal("Cancelled", "Operation Cancelled", "error");
			          }
			        })
				}
			})

			$('#cancel').on('click',function(){
				window.location.href = "{{ url('purchaseorder') }}"
			})

			$('#purchaseorder').autocomplete({
				source: "{{ url('get/purchaseorder/all') }}"
			})

			$('#stocknumber').on('change',function(){
					$.ajax({
						type: 'get',
						url: '{{ url('get/supply') }}' +  '/' + $('#stocknumber').val() + '/balance',
						dataType: 'json',
						success: function(response){
							try{
								details = response.data[0].supplytype
								$('#supply-item').val(details.toString())
								$('#stocknumber-details').html(`
									<div class="alert alert-info">
										<ul class="list-unstyled">
											<li><strong>Item:</strong> ` + response.data[0].supplytype + ` </li>
										</ul>
									</div>
								`)

								$('#add').show()
							} catch (e) {
								$('#stocknumber-details').html(`
									<div class="alert alert-danger">
										<ul class="list-unstyled">
											<li>Invalid Property Number</li>
										</ul>
									</div>
								`)

								$('#add').hide()
							}
						}
				})
			})

			$( "#date" ).datepicker({
				  changeMonth: true,
				  changeYear: false,
				  maxAge: 59,
				  minAge: 15,
			});

			@if(Input::old('date'))
				$('#date').val('{{ Input::old('date') }}');
				setDate("#date");
			@else
				$('#date').val('{{ Carbon\Carbon::now()->toFormattedDateString() }}');
				setDate("#date");
			@endif

			$('#add').on('click',function(){
				row = parseInt($('#supplyTable > tbody > tr:last').text())
				if(isNaN(row))
				{
					row = 1
				} else row++

				stocknumber = $('#stocknumber').val()
				quantity = $('#quantity').val()
				details = $('#supply-item').val()
				unitprice = $('#unitprice').val()
				addForm(row,stocknumber,details,quantity,unitprice)
				$('#stocknumber').text("")
				$('#quantity').text("")
				$('#unitprice').text("")
				$('#stocknumber-details').html("")
				$('#stocknumber').val("")
				$('#quantity').val("")
				$('#unitprice').val("")
				$('#add').hide()
			})

			function addForm(row,_stocknumber = "",_info ="" ,_quantity = "",_unitprice = "")
			{
				$('#supplyTable > tbody').append(`
					<tr>
						<td><input type="text" class="form-control text-center" value="` + _stocknumber + `" name="stocknumber[` + _stocknumber + `]" style="border:none;" /></td>
						<td>` + _info + `</td>
						<td><input type="number" class="form-control text-center" value="` + _quantity + `" name="quantity[` + _stocknumber + `]" style="border:none;"  /></td>
						<td><input type="number" class="form-control text-center" value="` + _unitprice + `" name="unitprice[` + _stocknumber + `]" style="border:none;"  /></td>
						<td><button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button></td>
					</tr>
				`)
			}

			@if(Input::has('stocknumber'))
				{{ $quantity = Input::old('quantity') }}
				{{ $unitprice = Input::old('unitprice') }}
				@foreach(Input::old('stocknumber') as $stocknumber)
				addForm('{{ $stocknumber }}','','{{ $quantity["$_stocknumber"] }}','{{ $unitprice["$_stocknumber"] }}')
				@endforeach
			@endif

			$('#date').on('change',function(){
				setDate("#date");
			});

			$('#supplyTable').on('click','.remove',function(){
				$(this).parents('tr').remove()
			})

			function setDate(object){
					var object_val = $(object).val()
					var date = moment(object_val).format('MMM DD, YYYY');
					$(object).val(date);
			}

			@if( Session::has("success-message") )
				swal("Success!","{{ Session::pull('success-message') }}","success");
			@endif

			@if( Session::has("error-message") )
				swal("Oops...","{{ Session::pull('error-message') }}","error");
			@endif

			$('#page-body').show()
		})
		</script>
@endsection
