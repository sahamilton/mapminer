<select class="form-control" 
name="companies[]"
    id="companies"
    multiple>
    @foreach ($companies as $company)
    <option value="{{$company->id}}"
        @if(isset($campaign) && in_array($company->id, $campaign->companies->pluck('id')->toArray()))
            selected
        @endif >
        {{$company->companyname}}
    </option>
    @endforeach
</select>