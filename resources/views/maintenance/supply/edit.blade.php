@extends('layouts.master')
@section('title')
Supply | Edit
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
			{{ Form::open(['method'=>'put','route'=>array('supply.update',$supply->stocknumber),'class'=>'form-horizontal']) }}
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
				<li class="active">{{ $supply->stocknumber }}</li>
				<li class="active">Edit</li>
			</ul>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Stock Number') }}
					{{ Form::text('stocknumber',Input::old('stocknumber'),[
						'id' => 'stocknumber',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Entity Name') }}
					{{ Form::text('entityname',"Polytechnic University Of the Philippines",[
						'id' => 'entityname',
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
					{{ Form::label('Fund Cluster') }}
					{{ Form::text('fundcluster',Input::old('fundcluster'),[
						'id' => 'fundcluster',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Unit Of Measurement') }}
					{{ Form::text('unit',Input::old('unit'),[
						'id' => 'unit',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Price') }}
					{{ Form::text('price',Input::old('price'),[
						'id' => 'price',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{{ Form::label('Reorder Point') }}
					{{ Form::number('reorderpoint',Input::old('reorderpoint'),[
						'id' => 'reorderpoint',
						'class' => 'form-control'
					]) }}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<button class="btn btn-lg btn-primary btn-block">UPDATE</button>
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

	@if(Auth::user()->accesslevel == 1)
	$('#stocknumber,#entityname,#reorderpoint,#unit,#item').prop('readonly','readonly')
	@else
	$('#price,#fundcluster').prop('readonly','readonly')
	@endif

	$('#entityname').val("{{ $supply->entityname }}")
	$('#stocknumber').val("{{ $supply->stocknumber }}")
	$('#fundcluster').val("{{ $supply->fundcluster }}")
	$('#unit').val("{{ $supply->unit }}")
	$('#price').val("{{ $supply->unitprice }}")
	$('#reorderpoint').val("{{ $supply->reorderpoint }}")
	$('#item').val("{{ $supply->supplytype }}")
	
	$('#page-body').show()
})
</script>
@stop
