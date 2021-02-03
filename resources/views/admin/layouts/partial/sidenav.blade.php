<section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu" data-widget="tree">
		<li class="header">MAIN NAVIGATION</li>
		@php
		use App\Rback;
		use Illuminate\Support\Facades\DB;
		$module_arr = config('static_arrays.module_arr');

		$role_id = session('user.ses_role_lookup_pk_no');
		$role_permission_sql = DB::table('s_rbac')
		->join('s_pages', 's_rbac.page_pk_no', '=', 's_pages.page_pk_no')
		->select('s_rbac.*', 's_pages.page_name', 's_pages.page_route','s_pages.module_lookup_pk_no')
		->where(["s_rbac.role_lookup_pk_no" => $role_id, "s_rbac.row_status" => 1])
		->get();

		$role_permission = [];
		foreach ($role_permission_sql as $permission) {
			$role_permission[$permission->module_lookup_pk_no][$permission->page_name][] = $permission->page_route;
		}
		@endphp

		@foreach($module_arr as $module_id => $module)
		@if(isset($role_permission[$module_id]))

		@if($module == "Dashboard")
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="fa fa-circle-o"></i> Dashboard
			</a>
		</li>
		@else
		<li class="treeview">

			<a href="#">
				<i class="fa fa-hand-grab-o"></i>
				<span>{{ $module  }}</span>
				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
			</a>
			<ul class="treeview-menu">
				@if(isset($role_permission[$module_id]))
				@foreach($role_permission[$module_id] as $page => $route)
				<li>
					<a href="{{ route($route[0]) }}">
						<i class="fa fa-circle-o"></i>
						{{ $page }}
					</a>
				</li>
				@endforeach
				@endif
			</ul>
			
		</li>
		@endif
		
		@endif
		@endforeach
	</ul>
</section>
