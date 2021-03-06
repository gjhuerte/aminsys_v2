@extends('layouts.master')
@section('title')
Create
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-two, #page-body{
    display: none;
  }
</style>
@endsection
@section('content')
{{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'maintenance.item.type.store','id'=>'itemTypeForm')) }}
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <div class="panel panel-body ">
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
        <div id="page-one">
          <ol class="breadcrumb">
              <li>
                  <a href="{{ url('maintenance/item/type') }}">Item Type</a>
              </li>
              <li class="active">Create</li>
          </ol>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('name','Item Type Name') }}
              {{ Form::text('name',Input::old('name'),[
                'class'=>'form-control',
                'placeholder'=>'Item name'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('description','Description') }}
              {{ Form::textarea('description',Input::old('description'),[
                'class'=>'form-control',
                'placeholder'=>'Description'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <button id="submit" class="btn btn-block btn-flat btn-md btn-primary" type="button" style="padding:10px;">
                <span class="glyphicon glyphicon-share-alt"></span> <span class="hidden-xs">Submit</span>
              </button>
            </div>
          </div>
        </div> <!-- end of page one -->
      </div> <!-- centered  -->
    </div>
  </div>
</div><!-- Container -->
{{ Form::close() }}
@stop
@section('script')
<script>
  $(document).ready(function(){

    $('#next').click(function(){
      $('#page-one').hide(400);
      $('#page-two').show(400);
    });

    $('#previous').click(function(){
      $('#page-two').hide(400);
      $('#page-one').show(400);
    });

    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#submit').click(function(){
      swal({
        title: "Are you sure?",
        text: "This will submit an item type with the following fields.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, submit it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },function(confirm){
        if(confirm){
          $('#itemTypeForm').submit();
        }else{
          swal('Cancel','Operation Cancelled','error');
        }
      });
    });

    $('.add').click(function(){
      var data =  $('.add').data('id') + 1;
      $('#fieldList').append(`
            <div class="col-xs-12" id="field`+data+`">
              <div class="form-group col-md-12">
                <div class="input-group">
                  <input type="text" class="form-control field-remove" name="form`+data+`" placeholder="Field Name">
                  <span class="input-group-btn">
                    <button class="btn btn-danger btn-round btn-remove" type="button" role="button" id="`+data+`">
                      <span class="glyphicon glyphicon-minus" data-id = '`+data+`' id="`+data+`"></span>
                    </button>
                  </span>
                </div>
              </div>
            </div>`);

      $('.add').data('id',data);
      $('#totalFields').val(data+1);
    });

    $('#fieldList').on('click','button.btn-remove',function(event){
      if($('div#fieldList').children('div').length > 1)
        $('#field'+event.target.id).remove();
      else
        swal('Error','There must be atleast one (1) field','error');
    });

    $('#page-body').show();
  });
</script>
@stop
