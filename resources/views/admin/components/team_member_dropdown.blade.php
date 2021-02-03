<select class="form-control select2" name="team_lead" style="width: 100%;"
        aria-hidden="true">
    <option selected="selected" value="0">Select Team Lead</option>
    @if(!empty($team_arr))
        @foreach ($team_arr as $team_id => $team)
            <option value="{{ $team_id }}" selected="selected">{{ $team }}</option>
        @endforeach
    @endif
</select>