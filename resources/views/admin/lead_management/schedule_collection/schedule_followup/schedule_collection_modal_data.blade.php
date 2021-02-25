	<div class="nav-tabs-custom mb-0">
		<ul class="nav nav-tabs" id="tab_container">
			<li class="active">
				<a href="#schedule_followup" data-toggle="tab" data-type="1" data-action="{{ route('load_followup_modal') }}" aria-expanded="true">Schedule Followup</a>
			</li>
			<li class="">
				<a href="#schedule_collection" data-toggle="tab" data-type="2" data-action="{{ route('load_followup_modal') }}" aria-expanded="false">Schedule Collection</a>
			</li>
			<li class="">
				<a href="#compeleted_collection" data-toggle="tab" data-type="3" data-action="{{ route('load_followup_modal') }}" aria-expanded="false">Completed Collection</a>
			</li>
		</ul>

		<div class="tab-content" id="list-body">
			@include("admin.lead_management.schedule_collection.schedule_followup.schedule_followup")
			@include("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal")
			@include("admin.lead_management.schedule_collection.schedule_followup.completed_collection")
		</div>
	</div>

	<div class="modal-footer text-right">
		<button type="button" class="btn btn-xs bg-red" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-xs bg-blue">Save changes</button>
	</div>
