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
		<legend><h3 class="text-muted">Offices</h3></legend>
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
        {{ Form::open(array('class' => 'col-md-offset-3 col-md-6  form-horizontal','method'=>'post','route'=>'office.store','id'=>'officeForm')) }}
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
        <div class="" style="padding:10px;">
          <ol class="breadcrumb">
              <li>
                  <a href="{{ url('maintenance/office') }}">Office</a>
              </li>
              <li class="active">Create</li>
          </ol>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('deptcode','Department Code') }}
              {{ Form::text('deptcode',Input::old('deptcode'),[
                'class'=>'form-control',
                'placeholder'=>'Department Code'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('deptname','Department Name') }}
              {{ Form::text('deptname',Input::old('deptname'),[
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
      </div> <!-- centered  -->
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

<script>
  $(document).ready(function(){

    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif

    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $('#page-body').show();
  });
</script>
@endsection
