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
	<h1>Sales Approval</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Sales Approval</a></li>
		<li class="active">Sales Approval</li>
	</ol>
</section>

<section class="content">
	<div class="header">
		<table class="w-100">
			<tr>
				<td class="text-right w-25 border-0">
					{{-- <img class="logo" src="{{ public_path().'/backend/images/logo.png' }}"> --}}
				</td>
				<td class="text-center w-50 border-0">
					<div class="information mb-15">
						<h3 class="company_names">UNIMASS HOLDINGS LIMITED</h3>
						<div class="company_address">House 18, Kazi Nazrul Islam Avenue, Shahbagh, Dhaka-1000</div>
						{{-- <div class="hospital-contact">TEL : DMO-8750011 Ext-3322, Information: 8750011 Ext-4999, </div> --}}
						<div class="report_name">SALES APPROVAL</div>
					</div>
				</td>
				<td class="text-left w-25 border-0"></td>
			</tr>
		</table>
	</div>

	<div class="row">
		<div class="col-md-12 mb-15">
			<table class="table table-borderless">
				<tr>
					<th>Project Name:</th>
					<td><strong>Dale Adenia</strong> <br> 42,43, Dilu Road, Eskaton, Dhaka</td>
					<th>Facing of Apartment:</th>
					<td>North-East (Back Side)</td>
				</tr>

				<tr>
					<th>Type of Apartment:</th>
					<td>B</td>

					<th>Apartment Position:</th>
					<td>3rd Floor</td>
				</tr>

				<tr>
					<th>Apartment No.:</th>
					<td>3</td>

					<th>Proposed Selling Price:</th>
					<td>0.00 Tk/sft</td>
				</tr>

				<tr>
					<th>Client's Name & Profession:</th>
					<td></td>

					<th>Standard Price:</th>
					<td>0.00 Tk/sft</td>
				</tr>

				<tr>
					<th>Plinth Area:</th>
					<td>1556sft</td>

					<th>Note:</th>
					<td>1. Booking Money Tk7,000,000<br>2. Allotted Car park No.</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="w-100">
				<thead class="bg-light-theme">
					<tr>
						<th class="text-center" colspan="10">Comparative Statement for Payment Schedule</th>
					</tr>
					<tr>
						<th class="text-center" colspan="3">Approved Rate</th>
						<th class="text-center" colspan="3">Proposed Rate</th>
						<th class="text-center" colspan="4">Interest Calculation</th>
					</tr>

					<tr>
						<th class="text-center">Investment Date</th>
						<th class="text-center">%</th>
						<th class="text-right">Amount</th>
						<th class="text-center">Collection  Date</th>
						<th class="text-center">%</th>
						<th class="text-right">Amount</th>
						<th class="text-center">Total Product</th>
						<th class="text-center">Month</th>
						<th class="text-center">Rate</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
					<tr>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">30%</td>
						<td class="font-weight-normal text-right">247,500</td>
						<td class="font-weight-normal text-center">September-18</td>
						<td class="font-weight-normal text-center">0</td>
						<td class="font-weight-normal text-right"></td>
						<td class="font-weight-normal text-center">247,500</td>
						<td class="font-weight-normal text-center">1</td>
						<td class="font-weight-normal text-center">10</td>
						<td class="font-weight-normal text-right">2,063</td>
					</tr>
				</tbody>

				<tfoot class="bg-light-theme">
					<tr>
						<th class="text-left">Total :</th>
						<th class="text-center">1</th>
						<th class="text-right">825,000</th>
						<th class="text-center"></th>
						<th class="text-center">100</th>
						<th class="text-right">12,961,800</th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-center"></th>
						<th class="text-right">(272,874)</th>
					</tr>
					<tr>
						<th class="text-left" colspan="4">Per Sft Gain/(Loss):</th>
						<th class="text-center">
						</th>
						<th class="text-right">Tk/sft</th>
						<th class="text-right">175</th>
						<th class="text-right" colspan="3"></th>
					</tr>

					<tr>
						<th class="text-left" colspan="4">Actual Selling Price:</th>
						<th class="text-center">
							<div class="">(12,136,800)</div>
							<div class="">(577,942.86)</div>
						</th>
						<th class="text-right">Tk/sft</th>
						<th class="text-right">175</th>
						<th class="text-right" colspan="3"></th>
					</tr>
					<tr>
						<th class="text-left" colspan="4">Net Profit/(Loss): (From Management Approved Price)</th>
						<th class="text-center"></th>
						<th class="text-right">Taka</th>
						<th class="text-right"> (12,953,126)</th>
						<th class="text-right" colspan="3"></th>
					</tr>
					<tr>
						<th class="text-left" colspan="4">% of Profit/(Loss): (From Standard Price)</th>
						<th class="text-center"></th>
						<th class="text-right">%</th>
						<th class="text-right">(98)</th>
						<th class="text-right" colspan="3"></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 mb-15 mt-20">
			<table class="table table-borderless">
				<tr>
					<th class="text-left"><strong>Sale Conducted by: Mr. S M Jauhan, Executive-CR</strong></th>
					<td class="text-center">
						<img src="{{ asset('backend/images/sign.png') }}" alt="">
						<b class="border-top d-block">Signature</b>
					</td>
				</tr>
				<tr>
					<th class="text-left"><strong>Prepared By: Head of Team- CR</strong></th>
					<td class="text-right"><strong>Forwarded By: General Manager-CR</strong></td>
				</tr>
				<tr>
					<th class="text-left"><strong>Recommended By: Director-Finance & Sales</strong></th>
					<td class="text-right"><strong>Approved By: Managing Director</strong></td>
				</tr>



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