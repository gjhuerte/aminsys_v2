@extends('layouts.master')
@section('title')
Office | Edit
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
{{ Form::open(array('class' => 'form-horizontal','method'=>'put','route'=>array('office.update',$office->deptcode),'id'=>'officeForm')) }}
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
                  <a href="{{ url('maintenance/office') }}">Office</a>
              </li>
              <li class="active">{{ $office->deptcode }}</li>
              <li class="active">Edit</li>
          </ol>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('deptcode','Department Code') }}
              {{ Form::text('deptcode',Input::old('deptcode'),[
                'id' => 'deptcode',
                'class'=>'form-control',
                'placeholder'=>'Department Code'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('deptname','Department Name') }}
              {{ Form::text('deptname',Input::old('deptname'),[
                'id' => 'deptname',
                'class'=>'form-control',
                'placeholder'=>'Department Name'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <button id="submit" class="btn btn-block btn-lg btn-md btn-primary" type="submit" style="padding:10px;">
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
    
    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#deptcode').val("{{ $office->deptcode }}")
    $('#deptname').val("{{ $office->deptname }}")

    $('#page-body').show();
  });
</script>
@stop
