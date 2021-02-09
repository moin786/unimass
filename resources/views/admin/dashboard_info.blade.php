@php
$ses_other_user_id  = Session::get('user.ses_other_user_pk_no');
$ses_other_user_name  = Session::get('user.ses_other_full_name');
$ses_role_lookup_pk_no  = Session::get('user.ses_role_lookup_pk_no');
$user_type  = Session::get('user.user_type');
$role_id = Session::get('user.ses_role_lookup_pk_no');
@endphp
<div class="form-row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<a href="{{ route('lead_list', 1) }}" class="small-box-footer">
				<span class="info-box-icon bg-maroon"><i class="fa fa-hand-rock-o"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Leads</span>
					<span
					class="info-box-number">{{ isset($lead_count[0]->total_lead)?$lead_count[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<a href="{{ route('lead_list', 3) }}" class="small-box-footer">
				<span class="info-box-icon bg-aqua"><i class="fa fa-building-o"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Cool</span>
					<span class="info-box-number">{{ isset($k1[0]->total_lead)?$k1[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', 13);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer">
				<span class="info-box-icon bg-red"><i class="fa fa-hand-o-up"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">HOT</span>
					<span class="info-box-number">{{ isset($hp[0]->total_lead)?$hp[0]->total_lead : 0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', 4);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer">
				<span class="info-box-icon bg-yellow"><i class="fa  fa-star-o"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Warm</span>
					<span
					class="info-box-number">{{ isset($priority[0]->total_lead)?$priority[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', [13,1]);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer routeSetUp">
				<span class="info-box-icon bg-navy"><i class="fa fa-exchange"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Transferred</span>
					<span
					class="info-box-number">{{ isset($transferred[0]->total_lead)?$transferred[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', 7);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Sold</span>
					<span
					class="info-box-number">{{ isset($sold[0]->total_lead)?$sold[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', 5);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer">
				<span class="info-box-icon bg-orange"><i class="fa fa-pause"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Hold</span>
					<span
					class="info-box-number">{{ isset($hold[0]->total_lead)?$hold[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			@php
			$hp_link = ($role_id == 75)?'#':route('lead_list', 6);
			@endphp
			<a href="{{ $hp_link }}" class="small-box-footer">
				<span class="info-box-icon bg-red"><i class="fa fa-times-circle-o"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Closed</span>
					<span
					class="info-box-number">{{ isset($closed[0]->total_lead)?$closed[0]->total_lead:0 }}</span>
				</div>
			</a>
		</div>
	</div>
</div>

@if($other_user_id == null)
<div class="form-row">
	<section class="col-lg-4 connectedSortable">
		<div class="box box-primary">
			{{--                 <div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Source Wise Summary</h3>
			</div> --}}
			<div class="box-body">
				<table class="table table-bordered table-striped dataTable m-0">
					<thead>
						<tr>
							<th><i class="ion ion-clipboard"></i>&nbsp; Source Wise Summary</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<a href="{{ route("stage_wise_lead_list",1) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">All MQL</span>

									@if($mql->lead_count == 0)
									<small class="label label-default pull-right">{{ $mql->lead_count }}</small>
									@else
									<small class="label label-success pull-right">{{ $mql->lead_count }}</small>
									@endif
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="{{ route("stage_wise_lead_list",2) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">All Walkin</span>

									@if($walkin->lead_count == 0)
									<small
									class="label label-default pull-right">{{ $walkin->lead_count }}</small>
									@else
									<small
									class="label label-success pull-right">{{ $walkin->lead_count }}</small>
									@endif
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="{{ route("stage_wise_lead_list",3) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>

									<span class="text">All SGL</span>

									@if($sgl->lead_count == 0)
									<small class="label label-default pull-right">{{ $sgl->lead_count }}</small>
									@else
									<small class="label label-success pull-right">{{ $sgl->lead_count }}</small>
									@endif
								</a>
							</td>
						</tr>
						@if($role_id == 77)
						<tr>
							<td>
								<a href="{{ route("my_lead_list") }}" class="routeSetUp"> <span class="handle">
									<i class="fa fa-ellipsis-v"></i>
									<i class="fa fa-ellipsis-v"></i></span>
									<span class="text">My Leads</span>
									@if($my_lead->lead_count == 0)
									<small
									class="label label-default pull-right">{{ $my_lead->lead_count }}</small>
									@else
									<small
									class="label label-success pull-right">{{ $my_lead->lead_count }}</small>
									@endif
								</a>
							</td>
						</tr>
						@endif
						<tr>
							<td>
								<a href="{{ route("todays_visit_lead") }}" class="routeSetUp"> <span class="handle">
									<i class="fa fa-ellipsis-v"></i>
									<i class="fa fa-ellipsis-v"></i></span>
									<span class="text">Total Visit</span>
									@if($meeting_count == 0)
									<small class="label label-default pull-right">{{ $meeting_count }}</small>
									@else
									<small class="label label-success pull-right">{{ $meeting_count }}</small>
									@endif
								</a>
							</td>
						</tr>

					</tbody>
				</table>

				{{-- <ul class="todo-list">
					<li>
						<a href="{{ route("stage_wise_lead_list",1) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All MQL</span>

							@if($mql->lead_count == 0)
							<small class="label label-danger pull-right">{{ $mql->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $mql->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",2) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All Walkin</span>

							@if($walkin->lead_count == 0)
							<small class="label label-danger pull-right">{{ $walkin->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $walkin->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",3) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>

							<span class="text">All SGL</span>

							@if($sgl->lead_count == 0)
							<small class="label label-danger pull-right">{{ $sgl->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $sgl->lead_count }}</small>
							@endif
						</a>
					</li>
				</ul> --}}
			</div>
		</div>

		<div class="box box-primary">
			{{--                 <div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Junk Lead List</h3>
			</div> --}}
			<div class="box-body">
				<table class="table table-bordered table-striped dataTable m-0">
					<thead>
						<tr>
							<th><i class="ion ion-clipboard"></i>&nbsp; Junk Lead List</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<a href="{{ route("junk_work_list",0) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">Junk</span>

									@if($junk[0]->total_lead==0)
									<small class="label label-default pull-right">{{ isset($junk[0]->total_lead)?$junk[0]->total_lead :0 }}</small>
									@else
									<small class="label label-success pull-right">{{ isset($junk[0]->total_lead)?$junk[0]->total_lead :0 }}</small>
									@endif
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="{{ route("junk_work_list",1) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">Junk MQL</span>

									@if($junk_mql[0]->total_lead== 0)
									<small
									class="label label-default pull-right">{{ isset($junk_mql[0]->total_lead)?$junk_mql[0]->total_lead :0 }}</small>
									@else
									<small
									class="label label-success pull-right">{{ isset($junk_mql[0]->total_lead)?$junk_mql[0]->total_lead :0 }}</small>
									@endif
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="{{ route("junk_work_list",2) }}" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>

									<span class="text">Junk Walking</span>

									@if($junk_walkin[0]->total_lead==0)
									<small class="label label-default pull-right">{{ isset($junk_walkin[0]->total_lead)?$junk_walkin[0]->total_lead :0 }}</small>
									@else
									<small class="label label-success pull-right">{{ isset($junk_walkin[0]->total_lead)?$junk_walkin[0]->total_lead :0 }}</small>
									@endif
								</a>
							</td>
						</tr>

						<tr>
							<td>
								<a href="{{ route("junk_work_list",3) }}" class="routeSetUp"> <span class="handle">
									<i class="fa fa-ellipsis-v"></i>
									<i class="fa fa-ellipsis-v"></i></span>
									<span class="text">Junk SGL</span>
									@if($junk_sgl[0]->total_lead == 0)
									<small
									class="label label-default pull-right">{{ $junk_sgl[0]->total_lead }}</small>
									@else
									<small
									class="label label-success pull-right">{{ $junk_sgl[0]->total_lead }}</small>
									@endif
								</a>
							</td>
						</tr>



					</tbody>
				</table>

				{{-- <ul class="todo-list">
					<li>
						<a href="{{ route("stage_wise_lead_list",1) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All MQL</span>

							@if($mql->lead_count == 0)
							<small class="label label-danger pull-right">{{ $mql->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $mql->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",2) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All Walkin</span>

							@if($walkin->lead_count == 0)
							<small class="label label-danger pull-right">{{ $walkin->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $walkin->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",3) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>

							<span class="text">All SGL</span>

							@if($sgl->lead_count == 0)
							<small class="label label-danger pull-right">{{ $sgl->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $sgl->lead_count }}</small>
							@endif
						</a>
					</li>
				</ul> --}}
			</div>
		</div>
		@if($role_id ==551 || $role_id ==77)
		<div class="box box-primary">
			{{--                 <div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Project Wise Lead List</h3>
			</div> --}}
			<div class="box-body">
				<table class="table table-bordered table-striped dataTable m-0">
					<thead>
						<tr>
							<th><i class="ion ion-clipboard"></i>&nbsp; Project Wise Lead List</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($project_name))
						@foreach($project_name as $project)
						<tr>
							<td>
								<a href="#" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">{{ $project->lookup_name }}</span>
									@php
									$project_count = isset($project_wise_count[$project->lookup_pk_no])?$project_wise_count[$project->lookup_pk_no] :0
									@endphp
									@if($project_count==0)
									<small class="label label-default pull-right">{{ $project_count }}</small>
									@else
									<small class="label label-success pull-right">{{ $project_count }}</small>
									@endif

								</a>
							</td>
						</tr>
						@endforeach
						@endif




					</tbody>
				</table>

				{{-- <ul class="todo-list">
					<li>
						<a href="{{ route("stage_wise_lead_list",1) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All MQL</span>

							@if($mql->lead_count == 0)
							<small class="label label-danger pull-right">{{ $mql->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $mql->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",2) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">All Walkin</span>

							@if($walkin->lead_count == 0)
							<small class="label label-danger pull-right">{{ $walkin->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $walkin->lead_count }}</small>
							@endif
						</a>
					</li>

					<li>
						<a href="{{ route("stage_wise_lead_list",3) }}">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>

							<span class="text">All SGL</span>

							@if($sgl->lead_count == 0)
							<small class="label label-danger pull-right">{{ $sgl->lead_count }}</small>
							@else
							<small class="label label-success pull-right">{{ $sgl->lead_count }}</small>
							@endif
						</a>
					</li>
				</ul> --}}
			</div>
		</div>
		@endif



	</section>

	<section class="col-lg-4 connectedSortable">

		{{--@if($ses_role_lookup_pk_no  == 74)
			<div class="nav-tabs-custom">
				<!-- Tabs within a box -->
				<ul class="nav nav-tabs pull-right">
					<li class="pull-left header"><i class="fa fa-area-chart"></i> Digital Marketting</li>
				</ul>
				<div class="tab-content no-padding">
					<div class="chart tab-pane active" id="avt_list">
						<table id="datatable" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th style=" min-width: 10px" class="text-center">Sub Source</th>
									<th style=" min-width: 70px" class="text-center">K1</th>
									<th style=" min-width: 70px" class="text-center">Priority</th>
									<th style=" min-width: 80px" class="texot-center">Sold</th>
									<th style=" min-width: 100px" class="text-center">Hold</th>
									<th style=" min-width: 100px" class="text-center">Closed</th>
									<th class="text-center"></th>
								</tr>
							</thead>

							<tbody>
								@if(!empty($digital_mkt))
								@foreach($digital_mkt as $dgmkt)
								<tr>
									<td>{{ $dgmkt }}</td>
									<td class="text-center"></td>
									<td class="text-center"></td>
									<td class="text-center"></td>
									<td class="text-center"></td>
									<td class="text-center"></td>
								</tr>
								@endforeach
								@else
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
			@else
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs pull-right">
					<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: AVT</li>
				</ul>
				<div class="tab-content no-padding">
					<div class="chart tab-pane active" id="avt_list">
						@include('admin.components.avt_user_list')
					</div>
				</div>
			</div>
			@endif--}}
			{{--     <!-- /.nav-tabs-custom -->
				@if($user_type == 2)
				<!-- Custom tabs (Charts with tabs)-->
				<div class="nav-tabs-custom">
					<!-- Tabs within a box -->
					<ul class="nav nav-tabs pull-right">
						<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: APT</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane active" id="avt_list">
							@include('admin.components.apt_user_list')
						</div>
					</div>
				</div>
				<!-- /.nav-tabs-custom -->
				<!-- Custom tabs (Charts with tabs)-->
				<div class="nav-tabs-custom">
					<!-- Tabs within a box -->
					<ul class="nav nav-tabs pull-right">
						<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: ACR</li>
					</ul>
					<div class="tab-content no-padding">
						<div class="chart tab-pane active" id="avt_list">
							@include('admin.components.acr_user_list')
						</div>
					</div>
				</div>
				<!-- /.nav-tabs-custom -->
				@endif--}}

				<div class="box box-primary">
					<div class="box-header">
						<i class="ion ion-clipboard"></i>
						<h3 class="box-title">Sub Source Wise Summary</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="todo-list">
							@php
							$store =0;
							@endphp
							@foreach($source_wise_count_arr as $data=>$value)
							<li>
								<a href="#"> <span class="handle">
									<i class="fa fa-ellipsis-v"></i>
									<i class="fa fa-ellipsis-v"></i>
								</span>
								<span class="text">{{$data}}</span>
								@if( $value == 0)
								<small class="label label-default pull-right">{{ $value }}</small> </a>
								@else
								<small class="label label-success pull-right">{{ $value }}</small> </a>
								@endif
								@php
								$store =$store+ $value;
								@endphp
							</li>
							@endforeach
						</ul>
						<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->

					</div>
					<!-- /.box-body -->
				</div>


			</section>

			<section class="col-lg-4 connectedSortable">
				<!-- TO DO List -->

				<div class="box box-primary">
					<div class="box-header">
						<i class="ion ion-clipboard"></i>
						<h3 class="box-title">To Do List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="todo-list">

							@if($user_type == 2 || $is_super_admin ==1 )
							<li>
								<a href="{{ route("all_lead") }}" class="routeSetUp"> <span class="handle">
									<i class="fa fa-ellipsis-v"></i>
									<i class="fa fa-ellipsis-v"></i>
								</span>
								<span class="text">All Lead</span>
								@if($all_lead_count == 0)
								<small
								class="label label-default pull-right">{{ $all_lead_count }}</small>
								@else
								<small
								class="label label-success pull-right">{{ $all_lead_count }}</small>
								@endif


							</a>
						</li>
						@if($is_super_admin !=1)
						<li>
							<a href="{{ route("lead_follow_up_data",1) }}" class="routeSetUp">
								<span class="handle"><i class="fa fa-ellipsis-v"></i><i
									class="fa fa-ellipsis-v"></i>
								</span>
								<span class="text">Today Followup</span>
								@if( $today_followup  == 0)
								<small
								class="label label-default pull-right">{{ $today_followup }}</small>
								@else
								<small
								class="label label-success pull-right">{{ $today_followup }}</small>
								@endif
							</a>
						</li>
						<li>
							<a href="{{ route("lead_follow_up_data",3) }}">	<span
								class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">Next Followup</span>
							@if($next_followup == 0)
							<small
							class="label label-default pull-right">{{ $next_followup }}</small>
							@else
							<small
							class="label label-success pull-right">{{ $next_followup }}</small>
							@endif
						</a>
					</li>
					<li>
						<a href="{{ route("lead_follow_up_data", 2) }}"> <span
							class="handle">
							<i class="fa fa-ellipsis-v"></i>
							<i class="fa fa-ellipsis-v"></i>
						</span>
						<span class="text">Missed Followup</span>
						@if($missed_followup == 0)
						<small
						class="label label-default pull-right">{{ $missed_followup }}</small>
						@else
						<small
						class="label label-success pull-right">{{ $missed_followup }}</small>
						@endif
					</a>
				</li>
				@endif
				@endif
				@if ($is_hod == 1 || $is_hot == 1 || $is_tl == 1 || $ses_user_type==1 || $role_id == 551)
				<li>
					@if($ses_user_type==2)
					<a href="{{ route("lead_dist_list") }}" class="routeSetUp"> <span class="handle">
						<i class="fa fa-ellipsis-v"></i>
						<i class="fa fa-ellipsis-v"></i>
					</span>
					<span class="text">Undistribute Lead</span>
					@if( $distribute_lead_count == 0 )
					<small
					class="label label-default pull-right">{{$distribute_lead_count }}</small>
					@else
					<small
					class="label label-success pull-right">{{$distribute_lead_count }}</small>
					@endif
				</a>
				@elseif($ses_user_type==1)
				<a href="{{ route("lead.lead_distribution") }}"> <span class="handle">
					<i class="fa fa-ellipsis-v"></i>
					<i class="fa fa-ellipsis-v"></i>
				</span>
				<span class="text">Undistribute Lead</span>
				@if( $distribute_lead_count == 0 )
				<small
				class="label label-default pull-right">{{$distribute_lead_count }}</small>
				@else
				<small
				class="label label-success pull-right">{{$distribute_lead_count }}</small>
				@endif

			</a>
			@endif
		</li>
		@endif


                    <!-- <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">KYC Reminder</span>
                                    <small class="label label-warning pull-right">0</small>
                                </li> -->
                        <!-- <li>
                            <span class="handle">
                                <i class="fa fa-ellipsis-v"></i>
                                <i class="fa fa-ellipsis-v"></i>
                            </span>
                            <span class="text">Birthday wish</span>
                            <small class="label label-warning pull-right">0</small>
                        </li> -->
                    </ul>
                    <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->

                </div>
                <!-- /.box-body -->
            </div>

            <!-- /.box -->
        </section>


        <!-- /.Left col -->
    </div>
    @endif
