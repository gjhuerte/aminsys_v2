@extends('backpack::layout')

@section('after_styles')
    <!-- Ladda Buttons (loading buttons) -->
    <link href="{{ asset('vendor/backpack/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    {{ HTML::style(asset('css/sweetalert.css')) }}
    {{ HTML::style(asset('css/jquery-ui.css')) }}
    <style>

      #page-body,#add{
        display: none;
      }

      a > hover{
        text-decoration: none;
      }

      th , tbody{
        text-align: center;
      }
    </style>
@endsection

@section('header')
	<section class="content-header">
	  <h1>
	    Request Form
	  </h1>
	  {{-- <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}">Das</a></li>
	    <li class="active">{{ trans('backpack::backup.backup') }}</li>
	  </ol> --}}
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
    {{ Form::open(['method'=>'post','route'=>array('request.store'),'class'=>'col-sm-offset-2 col-sm-8 form-horizontal','id'=>'requestForm']) }}
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
      <div class="form-group" style="margin-top: 20px">
        <div class="col-md-12">
        {{ Form::label('stocknumber','Stock Number') }}
        {{ Form::text('stocknumber',null,[
          'id' => 'stocknumber',
          'class' => 'form-control'
        ]) }}
        </div>
      </div>
      <input type="hidden" id="supply-item" />
      <div id="stocknumber-details">
      </div>
      <div class="col-md-12">
        <div class="form-group">
        {{ Form::label('Quantity') }}
        {{ Form::text('quantity','',[
          'id' => 'quantity',
          'class' => 'form-control'
        ]) }}
        </div>
      </div>
      <div class="btn-group" style="margin-bottom: 20px">
        <button type="button" id="add" class="btn btn-md btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
      </div>
      <legend></legend>
      <table class="table table-hover table-condensed table-bordered" id="supplyTable">
        <thead>
          <tr>
            <th>Stock Number</th>
            <th>Information</th>
            <th>Quantity</th>
            <th></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="pull-right">
        <div class="btn-group">
          <button type="button" id="request" class="btn btn-md btn-primary btn-block">Request</button>
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
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    {{ HTML::script(asset('js/sweetalert.min.js')) }}

<script>
  jQuery(document).ready(function($) {


  $('#stocknumber').autocomplete({
    source: "{{ url("get/inventory/supply/stocknumber") }}"
  })

  $('#office').autocomplete({
    source: "{{ url('get/office/code') }}"
  })

  $('#request').on('click',function(){
    console.log($('#supplyTable > tbody > tr').length)
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
              $('#requestForm').submit();
            } else {
              swal("Cancelled", "Operation Cancelled", "error");
            }
          })
    }
  })

  $('#cancel').on('click',function(){
    window.location.href = "{{ url('inventory/supply') }}"
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
                  <li>Invalid Stock Number</li>
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
    addForm(row,stocknumber,details,quantity)
    $('#stocknumber').text("")
    $('#quantity').text("")
    $('#stocknumber-details').html("")
    $('#stocknumber').val("")
    $('#quantity').val("")
    $('#add').hide()
  })

  function addForm(row,_stocknumber = "",_info ="" ,_quantity = "")
  {
    $('#supplyTable > tbody').append(`
      <tr>
        <td><input type="hidden" class="form-control text-center" value="` + _stocknumber + `" name="stocknumber[` + _stocknumber + `]" style="border:none;background-color:white;" readonly />` + _stocknumber + `</td>
        <td><input type="hidden" class="form-control text-center" value="` + _info + `" name="info[` + _stocknumber + `]" style="border:none;" />` + _info + `</td>
        <td><input type="hidden" class="form-control text-center" value="` + _quantity + `" name="quantity[` + _stocknumber + `]" style="border:none;background-color:white;" readonly />` + _quantity + `</td>
        <td><button type="button" class="remove btn btn-md btn-danger text-center"><span class="glyphicon glyphicon-remove"></span></button></td>
      </tr>
    `)
  }

  $('#date').on('change',function(){
    setDate("#date");
  });

  $('#cancel').on('click',function(){
    window.location.href = "{{ url('request') }}"
  })

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

  @if(null !== old('stocknumber'))

    function init()
    {

    @foreach(old('stocknumber') as $stocknumber)
      row = parseInt($('#supplyTable > tbody > tr:last').text())
      if(isNaN(row))
      {
        row = 1
      } else row++

      addForm(row,"{{ $stocknumber }}","{{ old("info.$stocknumber") }}", "{{ old("quantity.$stocknumber") }}")
    @endforeach

    }

    init();

  @endif
  
  $('#page-body').show()

  });
</script>
@endsection
