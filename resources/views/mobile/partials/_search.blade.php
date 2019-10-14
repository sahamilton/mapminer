@php
$views = array('list'=>'list','map'=>'map');
$values = Config::get('app.search_radius');
$types= ['activities'=>'Activities','leads'=>'Leads','opportunities'=>'Opportunities'];
@endphp

<form class="form-inline" action="{{route('mobile.search')}}" 
method = 'post' name="mapselector">
@csrf

<label>Show a </label>

<select name='view' class="btn btn-mini" id="selectview" title="Select map or list views">    
    @foreach($views as $key=>$field)
      @if(isset($data['view']) && $key === $data['view'])
        <option selected value="{{$key}}">{{$key}}</option>
      @else
        <option value="{{$key}}">{{$key}}</option>
      @endif
    @endforeach
</select>
<label>of</label>
       <select name='type' class="btn btn-mini" id="selecttype" title="Select accounts, projects or branches">
            @foreach($types as $key=>$value)
                @if(isset($type) && $key === $type)
                        <option selected value="{{$key}}">{{$value}}</option>
                        @else
                    
                      <option value="{{$key}}">{{$value}}</option>
                @endif
           @endforeach
        </select>

<label>within </label>  

   <select name='distance' class="btn btn-mini" id="selectdistance" title="Change the search distance">
       @foreach($values as $value)
        @if(isset($distance) && $value === $distance)
          <option selected value="{{$value}}">{{$value}} miles</option>
            @else
          <option value="{{$value}}">{{$value}} miles</option>
            @endif
       @endforeach

    </select> 

  <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        <label for= "address"> from address </label> 

        <input 
        class="form-control{{ $errors->has('search') ? ' has-error' : ''}}" 
        type="text" 
        name="search" 
        
        value="{{$location}}"
        id="search"
        required
        style='width:300px'
        placeholder='Enter address or check Help Support for auto geocoding' />
       {!! $errors->first('search', '<p class="help-block">:message</p>') !!}
    </div>
<button type="submit"  style="background-color: #4CAF50;"
class= "btn btn-success ">

<i class="fas fa-search" aria-hidden="true"></i> Search!</button>

<input type="hidden" name ='company' value="{{isset($company) ? $company->id : ''}}" />
<input type="hidden" name ='companyname' value="{{isset($company) ? $company->companyname : ''}}" />
<input type="hidden" name="lng" id ="lng" value="{{session('geo.lng') ? session('geo.lng') : config('mapminer.default_lng')}}" />
<input type="hidden" name="lat" id ="lat" value="{{session('geo.lat')? session('geo.lat') : config('mapminer.default_lat')}}" />
</form>

