
        <div class="col-md-2">
            <div class="form-group">
                <label for="from_date">From </label>
                <input type="text" class="form-control datepicker" id="from_date"
                       name="from_date" value="" title="" placeholder="dd-mm-yyyy"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="to_date">To </label>
                <input type="text" class="form-control datepicker" id="to_date" name="to_date"
                       value="" title="" placeholder="dd-mm-yyyy"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="cluster_head">HOD </label>
                <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                        id="cluster_head" name="cluster_head">
                    <option value="">Select</option>
                    @if(!empty($cluster_head))
                        @foreach ($cluster_head as $value)
                            <option
                                value="{{ $value->user_pk_no }}">{{ $value->user_fullname }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cluster_head">Team Name</label>
                <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                        id="team_name" name="team_name">
                    <option value="">Select</option>
                    @if(!empty($team_name_arr))
                        @foreach ($team_name_arr as $team)
                            <option
                                value="{{ $team->team_lookup_pk_no }}">{{ $team->lookup_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cluster_head">Project Name</label>
                <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                        id="project_name" name="project_name">
                    <option value="">Select</option>
                    @if(!empty($project_name))
                        @foreach ($project_name as $key=>$value)
                            <option
                                value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cluster_head">Stage</label>
                <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                        id="stage" name="stage">
                    <option value="">Select</option>
                    @if(!empty($lead_stage_arr))
                        @foreach ($lead_stage_arr as $key=>$value)
                            <option
                                value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="cluster_head">Source</label>
                <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                        id="source" name="source">
                    <option value="">Select</option>
                    @if(!empty($source))
                        @foreach ($source as $key=>$value)
                            <option
                                value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <label></label>
            <button type="button" class="btn bg-green btn-sm form-control" id="btnSearchReport">
                Search
            </button>
        </div>
