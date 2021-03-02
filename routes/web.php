<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    return redirect('/admin/login');
});
Route::get('/home', function () {
    return redirect('/admin/dashboard');
});
Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
});

Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function () {
    Route::get('dashboard/{switch_user?}', 'DashboardController@index')->name('admin.dashboard');
    Route::get('performance_chart_data/user_id/{user_id}/type/{type}', 'DashboardController@performance_chart_data')->name('performance_chart_data');
    Route::resource('customer', 'CustomerController');
    Route::resource('category', 'CategoryController');
    Route::resource('attribute', 'AttributeController');
    Route::resource('supplier', 'SupplierController');

    Route::post('load_users', 'UserController@load_users')->name('load_users');
    Route::resource('user', 'UserController');

    Route::post('lookup_list', 'SettingsController@load_list')->name('lookup_list');
    Route::get('project_wise_flat', 'SettingsController@project_wise_flat')->name('project_wise_flat');
    Route::get('create_project_wise_flat', 'SettingsController@create_project_wise_flat')->name('create_project_wise_flat');
    Route::post('store_flat_setup', 'SettingsController@store_flat_setup')->name('store_flat_setup');
    Route::get('edit_project_wise_flat/{lead_id}', 'SettingsController@edit_project_wise_flat')->name('edit_project_wise_flat');
    Route::post('update_flat_setup', 'SettingsController@update_flat_setup')->name('update_flat_setup');
    Route::post('load_project_wise_flat_list', 'SettingsController@load_project_wise_flat_list')->name('load_project_wise_flat_list');
    Route::resource('settings', 'SettingsController');

    Route::get('import_csv', 'LeadController@import_csv')->name('import_csv');
    Route::post('store_import_csv', 'LeadController@store_import_csv')->name('store_import_csv');
    Route::post('store_import_csv1', 'LeadController@store_import_csv1')->name('store_import_csv1');
    Route::post('store_import_csv2', 'LeadController@store_import_csv2')->name('store_import_csv2');

    Route::get('lead_list/{lead_id}/{transfer_type?}/{from_dt?}/{to_dt?}', 'LeadController@lead_list')->name('lead_list');
    Route::post('lead_list_followup/{lead_id}/{transfer_type?}', 'LeadController@lead_list')->name('lead_list_followup');
    Route::get('lead_list_view/{lead_id}/{transfer_type?}', 'LeadController@lead_list_view')->name('lead_list_view');
    Route::get('lead_view/{lead_id}', 'LeadController@lead_view')->name('lead_view');
    Route::get('lead_edit/{lead_id}', 'LeadController@lead_edit')->name('lead_edit');
    Route::post('get_team_users', 'LeadController@get_team_users')->name('get_team_users');
    Route::post('load_area_project_size', 'LeadController@load_area_project_size')->name('load_area_project_size');
    Route::post('load_sales_agent_by_area', 'LeadController@load_sales_agent_by_area')->name('load_sales_agent_by_area');
    Route::post('check_if_phone_no_exist', 'LeadController@check_if_phone_no_exist')->name('check_if_phone_no_exist');
    Route::resource('lead', 'LeadController');

    Route::get('district-thana','DistrictThanaController@districtThana')->name('district-thana');

    Route::get('stage_update/{lead_id}', 'LeadFllowupController@stage_update')->name('stage_update');
    Route::post('store_stage_update', 'LeadFllowupController@store_stage_update')->name('store_stage_update');
    Route::get('lead_sold/{lead_id}', 'LeadFllowupController@lead_sold')->name('lead_sold');
    Route::post('store_lead_sold', 'LeadFllowupController@store_lead_sold')->name('store_lead_sold');
    Route::post('load_followup_leads', 'LeadFllowupController@load_followup_leads')->name('load_followup_leads');
    Route::get('lead_follow_up_from_dashboard/{lead_id}/{type}', 'LeadFllowupController@lead_follow_up_from_dashboard')->name('lead_follow_up_from_dashboard');
    Route::get("lead_follow_up_data/{id}/{from_dt?}/{to_dt?}", 'LeadFllowupController@index')->name("lead_follow_up_data");

    Route::resource('lead_follow_up', 'LeadFllowupController');

    Route::post('load_team_lead_by_team', 'TeamController@load_team_lead_by_team')->name('load_team_lead_by_team');
    Route::post('load_team_list_by_team', 'TeamController@load_team_list_by_team')->name('load_team_list_by_team');
    Route::post('remove_team', 'TeamController@remove_team')->name('remove_team');
    Route::get('team_target', 'TeamController@team_target')->name('team_target');
    Route::post('get_agent_by_type', 'TeamController@get_agent_by_type')->name('get_agent_by_type');
    Route::post('store_team_target', 'TeamController@store_team_target')->name('store_team_target');
    Route::resource('team', 'TeamController');

    Route::get('rbac', 'RbacController@index')->name('rbac');
    Route::get('rbac_pages/{role_id}', 'RbacController@rbac_pages')->name('rbac_pages');
    Route::get('rbac_assign/{role_id}/{page_id}', 'RbacController@rbac_assign')->name('rbac_assign');

    Route::get('search_engine', 'ReportController@index')->name('search_engine');
    Route::post('export_report', 'ReportController@export_report')->name('export_report');
    Route::post('search_result', 'ReportController@search_result')->name('search_result');

    //profile route
    Route::get('profile', 'ProfilesController@index')->name('profile');
    Route::post('profile/update', 'ProfilesController@update')->name('profile.update');

    Route::get('/lead_qc', 'LeadQcController@index')->name('lead_qc');
    Route::get('/lead_pass_junk', 'LeadQcController@lead_pass_junk')->name('lead_pass_junk');
    Route::get('/lead_bypass', 'LeadQcController@lead_bypass')->name('lead_bypass');
    Route::post('/load_qc_leads', 'LeadQcController@load_qc_leads')->name('load_qc_leads');

    Route::get('/lead_transfer', 'LeadTransferController@index')->name('lead_transfer');
    Route::post('/lead_create_transfer', 'LeadTransferController@lead_create_transfer')->name('lead_create_transfer');
    Route::post('/accept_transfer', 'LeadTransferController@accept_transfer')->name('accept_transfer');
    Route::post('/load_transfer_leads', 'LeadTransferController@load_transfer_leads')->name('load_transfer_leads');

    Route::get('/lead_dist_list/{from_dt?}/{to_dt?}', 'LeadDistribution@index')->name('lead_dist_list');
    Route::post('/distribute_lead', 'LeadDistribution@distribute_lead')->name('distribute_lead');
    Route::post('/distribute_junk_lead', 'LeadDistribution@distribute_junk_lead')->name('distribute_junk_lead');
    Route::get('/lead_auto_distribute', 'LeadDistribution@lead_auto_distribute')->name('lead_auto_distribute');
    Route::post('/load_dist_leads', 'LeadDistribution@load_dist_leads')->name('load_dist_leads');
    Route::post('/load_junk_leads', 'LeadDistribution@load_junk_leads')->name('load_junk_leads');

    // Stage wise attribute setup
    Route::get('/Stage_wise_attribute_list', 'StageWiseAttributeController@index')->name('Stage_wise_attribute_list');
    Route::get('/Stage_wise_attribute', 'StageWiseAttributeController@create')->name('Stage_wise_attribute');
    Route::get('/Stage_wise_attribute_edit/{id}', 'StageWiseAttributeController@editlist')->name('Stage_wise_attribute_edit');
    Route::get('/Stage_wise_attribute_alldata', 'StageWiseAttributeController@allData')->name('Stage_wise_attribute_alldata');
    Route::post('/Stage_wise_attribute_store', 'StageWiseAttributeController@store')->name('stage_wise_store');
    Route::patch('/Stage_wise_attribute_update/{id}', 'StageWiseAttributeController@upDate')->name('stage_wise_update');
    Route::get('/stage_wise_attribute_get', 'StageWiseAttributeController@stage_wise_attribute_get')->name('stage_wise_attribute_get');
    Route::get('/validation_setup', 'SettingsController@validation_setup')->name('validation_setup');
    Route::post('/ch_accept_transfer', 'LeadTransferController@ch_accept_transfer')->name('ch_accept_transfer');
    Route::get('/return_lead', 'LeadController@return_lead')->name('return_lead');
    Route::get('/reassign_lead', 'LeadController@reassign_lead')->name('reassign_lead');


    //Report
    Route::get('/stage_wise_user_report', 'ReportController@stage_wise_user_report')->name("report.stage_wise_user_report");
    Route::post('/stage_wise_user_report_result', 'ReportController@stage_wise_user_report_result')->name("report.stage_wise_user_report_result");
    Route::get('/daily_lead_report', 'ReportController@daily_lead_report')->name("report.daily_lead_report");
    Route::post('/daily_lead_report_result', 'ReportController@daily_lead_report_result')->name("report.daily_lead_report_result");
    Route::get('/source_report', 'ReportController@source_report')->name("report.source_report");
    Route::post('/source_report_result', 'ReportController@source_report_result')->name("report.source_report_result");
    Route::get('/project_report', 'ReportController@project_report')->name("report.project_report");
    Route::post('/project_report_result', 'ReportController@project_report_result')->name("report.project_report_result");
    Route::get('/monthly_lead_report', 'ReportController@monthly_lead_report')->name("report.monthly_lead_report");
    Route::post('/monthly_lead_report_result', 'ReportController@monthly_lead_report_result')->name("report.monthly_lead_report_result");

    //CRE Lead Distribution
    Route::get('/lead_distribution', 'LeadDistribution@lead_distribution_cre')->name("lead.lead_distribution");
    Route::post('/distribute_lead_to_ch', 'LeadDistribution@distribute_lead_to_ch')->name('distribute_lead_to_ch');
    Route::post('/load_dist_leads_to_ch', 'LeadDistribution@load_dist_leads_to_ch')->name('load_dist_leads_to_ch');
    Route::get('/all_lead/{from_dt?}/{to_dt?}', 'LeadController@all_lead')->name('all_lead');
    Route::get('/todays_visit_lead/{from_dt?}/{to_dt?}', 'LeadController@today_visit')->name('todays_visit_lead');

    //Report Export
    Route::post('export_daily_report', 'ReportController@export_daily_report')->name('export_daily_report');
    Route::post('export_report_stage_wise', 'ReportController@export_report_stage_wise')->name('export_report_stage_wise');
    Route::post('export_csv_source_report', 'ReportController@export_csv_source_report')->name('export_csv_source_report');
    Route::post('export_csv_project_report', 'ReportController@export_csv_project_report')->name('export_csv_project_report');
    Route::post('export_monthly_lead_report', 'ReportController@export_monthly_lead_report')->name('export_monthly_lead_report');

    Route::post('validation_setup_store', 'SettingsController@validation_setup_store')->name('validation.store');
    Route::post('validation_setup_update', 'SettingsController@validation_setup_update')->name('validation.update');

    Route::get('/lookup_type_wise_data', 'SettingsController@lookup_type_wise_data')->name('lookup_type_wise_data');

    Route::get('/block_list_lead', 'LeadDistribution@block_lead_list')->name("block_list_lead");
    Route::get('/approved_blocked_lead', 'LeadDistribution@approved_blocked_lead')->name("approved_blocked_lead");
    Route::get('/block_list_approved', 'LeadDistribution@block_list_approved')->name("block_list_approved");

    Route::post('/load_block_lead_list', 'LeadDistribution@load_block_lead_list')->name("load_block_lead_list");

    Route::get('/junk_work_list/{entry_type?}/{from_dt?}/{to_dt?}', 'LeadController@junk_Work_list')->name("junk_work_list");

    Route::get('/stage_wise_lead_list/{id}/{from_dt?}/{to_dt?}', 'LeadController@stage_wise_lead_list')->name("stage_wise_lead_list");
    Route::get('/my_lead_list/{from_dt?}/{to_dt?}', 'LeadController@my_lead_list')->name("my_lead_list");

    Route::post('/dashboard_info', 'DashboardController@dashboard_info')->name('dashboard_info');
    Route::get('/personal_lead_report', 'ReportController@personal_lead_report')->name('report.personal_lead_report');

    Route::post('/getTeamMembers', 'TeamController@getTeamMembers')->name("getTeamMembers"); //personal_report_result

    Route::post('/personal_report_result', 'ReportController@personal_lead_report_result')->name("report.personal_report_result");


    Route::get("/remove_double_number", "LeadController@remove_double_number")->name("remove_double_number");

    Route::get("/search_lead_data/{from_dt}/{to_dt}","LeadController@search_lead_data")->name("search_lead_data");
    Route::get("/ch_missing_lead","LeadController@ch_missing_lead")->name("ch_missing_lead");
    Route::get("/team_wise_lead_list","TeamController@leadList")->name("team_wise_lead_list");

    Route::get('/note_sheet_list', "LeadDistribution@note_sheet_list")->name("note_sheet_list");
    Route::post('/note_sheet_approve', "LeadDistribution@note_sheet_approve")->name("note_sheet_approve");
    Route::post('/load_note_sheet_list', "LeadDistribution@load_note_sheet_list")->name("load_note_sheet_list");


    Route::get('/district_thana_setup', "DistrictController@district_thana_setup")->name("district_thana_setup");  
    Route::get('/add_district_thana_popup', "DistrictController@add_district_thana_popup")->name("add_district_thana_popup");
    Route::post('/district-save', "DistrictController@storeDistrict")->name("district-save");
    Route::get('edit-district/{id}', 'DistrictController@edit_district')->name('district.edit');
    Route::post('/district-update', "DistrictController@updateDistrict")->name("district-update");
    Route::get('/district-delete/{id}', "DistrictController@delete")->name("district.delete");

    Route::get('/all-thana', "DistrictController@all_thana")->name("all_thana");
    Route::post('/thana_store', "DistrictController@storeThana")->name("thana_store");
    Route::get('edit-thana/{id}', 'DistrictController@edit_thana')->name('thana.edit');
    Route::post('/thana-update', "DistrictController@updateThana")->name("thana-update");
    Route::post('/district/get-thana-by-district', "DistrictController@getThanaByDistrict")->name("district.getThanaByDistrict");
    Route::get('/thana-delete/{id}', "DistrictController@deleteThana")->name("thana.delete");
    




    // schedule-controller
    Route::get('/schedule-collection', "projectScheduleController@scheduleController")->name("schedule-collection");
    Route::get('/lead_sold_view/{id}', "projectScheduleController@lead_sold_view")->name("lead_sold_view");
    Route::get('/collected_collection_view/{id}', "projectScheduleController@collected_collection_view")->name("collected_collection_view");

    Route::get('/collected_collection_view/{id}', "projectScheduleController@collected_collection_view")->name("collected_collection_view");

    Route::post('/store_schedule_collection', "projectScheduleController@store")->name("schedule-collection.store");

    Route::post('/store_schedule_followup', "ScheduleFollowupController@store")->name("store_schedule_followup.store");



    Route::post('/load_followup', 'projectScheduleController@load_schedule_collection')->name('load_followup');
    Route::post('/load_followup_modal', 'projectScheduleController@load_schedule_followup_modal')->name('load_followup_modal');
});
