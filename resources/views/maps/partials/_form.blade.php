<?php 
// Default values
$session = Session::get('geo');
if(! isset($session)) {
  if(Session::has('geo.type')){
  $session = array('type'=>'accounts','distance'=>'10','address'=>NULL,'view'=>'maps','lat'=>'39.8282','lng'=>'-98.5795');
  }else{
    $session = array('type'=>'accounts','distance'=>'10','address'=>NULL,'view'=>'maps','lat'=>'39.8282','lng'=>'-98.5795');
  }
}

foreach($session as $key=>$value)
{
	if(!isset($data[$key])){
		$data[$key] = $value;
	}
}
$types = ['location'=>'All accounts','branch'=>'Branches'];
// added to filter out Centerline
if(auth()->user()->can('view_projects') && in_array(5, Session::get('user.servicelines'))){
  $types['projects']='Construction projects';
}

if(isset($data['type']) && $data['type'] == 'company' && isset($company)){
	$types['company'] = $company->companyname .' locations';
}

$views = array('map'=>'map','list'=>'list');
$values = Config::get('app.search_radius');

?>
<form class="form-inline" action="{{route('findme')}}" method = 'post' name="mapselector">
{{csrf_field()}}
<label>Show a</label>
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
        				@if(isset($data['type']) && $key === $data['type'])
                        <option selected value="{{$key}}">{{$value}}</option>
                        @else
                    
                   		<option value="{{$key}}">{{$value}}</option>
        				@endif
           @endforeach
        </select>
<label>within</label>  
   <select name='distance' class="btn btn-mini" id="selectdistance" title="Change the search distance">
       @foreach($values as $value)
       	@if(isset($data['distance']) && $value === $data['distance'])
        	<option selected value="{{$value}}">{{$value}} miles</option>
            @else
       		<option value="{{$value}}">{{$value}} miles</option>
            @endif
       @endforeach
    </select> of 
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        <label for= "address">address</label> 
        <input 
        class="form-control{{ $errors->has('address') ? ' has-error' : ''}}" 
        type="text" 
        name="address" 
        title="Enter an address, zip code, or state code to search from"
        value="{{isset($data['address']) ? str_replace('+','', str_replace('  ',' ',$data['address'])) : ''}}"
        id="address" 
        required
        style='width:300px'
        placeholder='Enter address or check Help Support for auto geocoding' />
       {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
<button type="submit"  style="background-color: #4CAF50;"
class= "btn btn-success ">
<i class="fa fa-search" aria-hidden="true"></i> Search!</button>
<input type="hidden" name ='company' value="{{isset($company) ? $company->id : ''}}" />
<input type="hidden" name ='companyname' value="{{isset($company) ? $company->companyname : ''}}" />
<input type="hidden" name="lng" id ="lng" value="{{isset($data['lng']) ? $data['lng'] : '-98.5795'}}" />
<input type="hidden" name="lat" id ="lat" value="{{isset($data['lat']) ? $data['lat'] : '39.8282'}}" />
</form>
<?php $action = 'findme';?>
@include('partials._noaddressmodal')
<script>

$("#address").change(function() {
  $('#lat:first').val('');
  $('#lng:first').val('');
});


$("select[id^='select']").change(function() {
  if($.trim($('#address').val()) == ''){
    $( "#noaddress" ).modal('show');
    
  }else{
    
    this.form.submit();
}
});

</script>
