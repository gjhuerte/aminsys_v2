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
    {{ Form::open(['method'=>'put','route'=>array('request.update',$request->id),'class'=>'form-horizontal','id'=>'requestForm']) }}
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
      <legend><h3 class="text-muted">Request No. {{ $request->id }}</h3></legend>
      <table class="table table-hover table-condensed table-bordered" id="supplyTable">
        <thead>
          <tr>
            <th>Stock Number</th>
            <th>Information</th>
            <th>Requested Quantity</th>
            <th>Issued Quantity</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          @foreach($supplyrequest as $supplyrequest)
          <tr>
            <td>{{ $supplyrequest->stocknumber }}<input type="hidden" name="stocknumber[]" value="{{ $supplyrequest->stocknumber }}"</td>
            <td>{{ $supplyrequest->supply->supplytype }}</td>
            <td>{{ $supplyrequest->quantity_requested }}</td>
            <td><input type="number" name="quantity[{{ $supplyrequest->stocknumber }}]" class="form-control"  /></td>
            <td><input type="text" name="comment[{{ $supplyrequest->stocknumber }}]" class="form-control" /></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="pull-right">
        <div class="btn-group">
          <button type="button" id="approve" class="btn btn-md btn-success btn-block">Approve</button>
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

    $('#approve').on('click',function(){
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

    @if( Session::has("success-message") )
      swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
      swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#page-body').show()

  });
</script>
@endsection
