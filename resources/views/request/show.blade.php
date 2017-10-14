@extends('backpack::layout')

@section('after_styles')
    <!-- Ladda Buttons (loading buttons) -->
    <link href="{{ asset('vendor/backpack/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
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

    <!-- Bootstrap -->
    {{ HTML::style(asset('css/jquery-ui.css')) }}
    {{ HTML::style(asset('css/sweetalert.css')) }}
    {{ HTML::style(asset('css/dataTables.bootstrap.min.css')) }}
@endsection

@section('header')
	<section class="content-header">
		<legend><h3 class="text-muted">Request {{ $request->id }} Details</h3></legend>
		<ul class="breadcrumb">
			<li><a href="{{ url('request') }}">Request</a></li>
			<li class="active"> {{ $request->id }} </li>
		</ul>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-body">
		<div class="panel panel-body table-responsive">
			<table class="table table-hover table-striped table-bordered table-condensed" id="requestTable" cellspacing="0" width="100%"	>
				<thead>
            <tr rowspan="2">
                <th class="text-left" colspan="3">Request ID:  <span style="font-weight:normal">{{ $request->id }}</span> </th>
                <th class="text-left" colspan="3">Requestor:  <span style="font-weight:normal">{{ $request->requestor }}</span> </th>
            </tr>
            <tr rowspan="2">
                <th class="text-left" colspan="3">Remarks:  <span style="font-weight:normal">{{ $request->remarks }}</span> </th>
                <th class="text-left" colspan="3">Status:  <span style="font-weight:normal">{{ $request->status }}</span> </th>
            </tr>
            <tr>
						<th>Stock Number</th>
						<th>Details</th>
						<th>Quantity Requested</th>
						<th>Quantity Issued</th>
						<th>Comments</th>
					</tr>
				</thead>
			</table>
		</div>

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
	$(document).ready(function() {

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

    var table = $('#requestTable').DataTable({
			language: {
					searchPlaceholder: "Search..."
			},
			"processing": true,
			ajax: "{{ url("request/$request->id") }}",
			columns: [
					{ data: "stocknumber" },
					{ data: "supply.supplytype" },
					{ data: "quantity_requested" },
					{ data: "quantity_issued" },
					{ data: "comments" }
			],
    });

		$('#page-body').show();
	} );
</script>
@endsection
