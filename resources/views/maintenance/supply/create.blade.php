@extends('layouts.master')
@section('title')
Supply | Add
@stop
@section('navbar')
@include('layouts.navbar')
@stop
@section('style')
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
	<div class="col-md-offset-3 col-md-6 panel">
		<div class="panel-body">
			{{ Form::open(['method'=>'post','route'=>array('supply.store'),'class'=>'form-horizontal']) }}
			<legend><h3 class="text-muted">Supply</h3></legend>
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
			<ul class="breadcrumb">
				<li><a href="{{ url('maintenance/supply') }}">Supply</a></li>
				<li class="active">Add</li>
			</ul>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Stock Number') }}
					{{ Form::text('stocknumber',Input::old('stocknumber'),[
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Entity Name') }}
					{{ Form::text('entityname',"Polytechnic University Of the Philippines",[
						'class' => 'form-control',
						'readonly'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Supply Type') }}
					{{ Form::text('supplytype',Input::old('item'),[
						'id' => 'item',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Unit Of Measurement') }}
					{{ Form::text('unit',Input::old('unit'),[
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Reorder Point') }}
					{{ Form::number('reorderpoint',Input::old('reorderpoint'),[
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<button class="btn btn-lg btn-primary btn-block">ADD</button>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script-include')
<script>
$('document').ready(function(){

	$('#page-body').show()
})
</script>
@stop
