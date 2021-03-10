@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<style>
	.close {
		font-size: 27px;
	}

	.modal-header_view {
		border-bottom: 1px solid #ccc !important;
	}
	.modal-header_view {
		padding: 7px 15px;
	}
	.modal-footer {
		padding: 7px 15px;
	}
	/*========================================*/
	/*========================================*/
	@page {
		margin-top: 150px;
		margin-bottom: 100px
	}

	.header_view {
		position: fixed;
		left: 0px;
		top: -130px;
		right: 0px;
		height: 100px;
		text-align: center;
		border-bottom: 1px solid #16469f
	}

	.footer { 
		position: fixed;
		left: 0px;
		bottom: -50px;
		right: 0px;
	}

	body {
		/*margin-top: 150px;*/
		/*margin-left: .5cm;*/
		/*margin-right: .5cm;*/
		/*margin-bottom: 3.5cm;*/
		font-family: 'IBM Plex Sans,Helvetica,Arial,sans-serif';
		font-size: 11px;
	}

	.bg-light-theme{
		background-color: #dbe2ef ;
		color: #222;
	}


	table{
		border-collapse:collapse;

	}

	table tr td,
	table tr th{
		font-size: 13px;
		border:1px solid #a5adbd;
		/*font-family: 'IBM Plex Sans,Helvetica,Arial,sans-serif';*/
		padding: 3px;
	}
	.border-0{
		border:0px;
	}
	.text-center{
		text-align: center!important;
	}
	.text-left{
		text-align: left!important;
	}
	.text-right{
		text-align: right!important;
	}
	.w-100{
		width: 100%;
	}

	.w-5{
		width: 5%;
	}
	.w-10{
		width: 10%;
	}

	.w-25{
		width: 25%;
	}

	.w-50{
		width: 50%;
	}

	.w-75{
		width: 75%;
	}

	.w-100{
		width: 100%;
	}
	.mb-10{margin-bottom: 10px;}
	.mt-5 { margin-top: 5px; }
	.mt-10 { margin-top: 10px; }
	.mt-20 { margin-top: 20px; }
	.mb-0{ margin-bottom: 0; }
	.mb-15{ margin-bottom: 15px; }
	.pb-0{ padding-bottom: 0; }


	.logo{
		width: 80px;
		display: inline-block;
		float: right;
	}
	.d-flex{
		display: flex;
	}

	.align-items-center{
		align-items: center;
	}
	.company_names {
		margin-bottom: 0;
		font-size: 33px;
		padding-bottom: 0;
		color: #16469f
	}

	.information .company_address{
		display: block;
		font-size: 18px;
		line-height: 1.2;
		color: #16469f;
		font-weight: normal;
	}

	.information .report_name{
		display: block;
		font-size: 18px;
		line-height: 1.2;
		color: #16469f;
		margin-top:10px;
		margin-bottom: 15px;
		letter-spacing: 5px;
	}

	.text-uppercase{
		text-transform: uppercase; 
	}

	.title{
		/*background-color: #444;*/
		/*display: inline-block;*/
		font-size: 14px;
		color: #000;
		padding: 5px 10px;
		border-radius: 3px;
		font-weight: bold;
	}


	.table tr th,
	.table tr td {
		font-size: 14px;
		vertical-align: middle!important;
	}
	.table tr td {
		font-weight: normal!important;
	}
	.table tr th strong,
	.table tr td strong {
		font-size: 18px;
		vertical-align: middle!important;
	}



	.table-borderless tbody+tbody,
	.table-borderless td,
	.table-borderless th,
	.table-borderless thead th {
		border: 0;
	}
	.font-weight-normal{
		font-weight:normal!important;
	}

	.border-top{
		border-top: 1px solid #222 !important;
	}
	.d-block{
		display: block;
	}

	table tbody tr td img {
		width: 100px!important;
		margin-bottom: 5px;
	}
</style>

@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Balance of Month wise Receivable</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Balance of Month wise Receivable</a></li>
		<li class="active">Balance of Month wise Receivable</li>
	</ol>
</section>
<section class="content">
	<div class="header">
		<table class="w-100">
			<tr>
				<td class="text-right w-25 border-0"></td>
				<td class="text-center w-50 border-0">
					<div class="information mb-15">
						<h3 class="company_names">UNIMASS HOLDINGS LIMITED</h3>
						<div class="company_address">House 18, Kazi Nazrul Islam Avenue, Shahbagh, Dhaka-1000</div>
						<div class="report_name">BALANCE OF MONTH WISE RECEIVABLE</div>
					</div>
				</td>
				<td class="text-left w-25 border-0"></td>
			</tr>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="w-100">
				<thead class="bg-light-theme">
					<tr>
						<th class="text-center">Sl No</th>
						<th class="text-center">Project Code</th>
						<th class="text-left">Client Name</th>
						<th class="text-center">Apartment No</th>
						<th class="text-right">Total Receivable</th>
						<th class="text-right">Total Receivable</th>
						<th class="text-center">Jan-21</th>
						<th class="text-center">Feb-21</th>
						<th class="text-center">Mar-21</th>
						<th class="text-center">Apr-21</th>
						<th class="text-center">May-21</th>
						<th class="text-center">Jun-21</th>
						<th class="text-center">Jul-21</th>
						<th class="text-center">Aug-21</th>
						<th class="text-center">Sep-21</th>
						<th class="text-center">Oct-21</th>
						<th class="text-center">Nov-21</th>
						<th class="text-center">Dec-21</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="font-weight-normal text-center">01</td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-left"></td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">02</td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-left"></td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">03</td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-left"></td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">04</td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-left"></td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">05</td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-left"></td>
						<td class="font-weight-normal text-center"></td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-right">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
						<td class="font-weight-normal text-center">0.00</td>
					</tr>
				</tbody>
				<tfoot class="bg-light-theme">
					<tr>
						<th colspan="4"></th>
						<th class="text-right">0.00</th>
						<th class="text-right">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
					</tr>
					<tr>
						<th colspan="4"></th>
						<th class="text-right">0.00</th>
						<th class="text-right">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
						<th class="text-center">0.00</th>
					</tr>


				</tfoot>
			</table>
		</div>
	</div>

	<div class="footer">
		<table style="width: 100%; border-top: 1px solid;">
			<tr>
				<td class="border-0" width="37%">Printing Date & Time : {{ date("d/m/Y h:i:s a") }}</td>
				<td class="border-0"></td>
				<td class="border-0" width="33%" style="text-align: right;">Powered By - Logic Software Ltd.</td>
			</tr>
		</table>
	</div>
</section>

@endsection

@push('js_lib')

@endpush

@push('js_custom')

@endpush