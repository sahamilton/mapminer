<?php 
// Default values
// 

$session = Session::get('geo');
if(! isset($session)) {
  if(Session::has('type')){

  $session = array('type'=>Session::get('type'),'distance'=>'10','address'=>NULL,'view'=>'maps','lat'=>'39.8282','lng'=>'-98.5795');
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
// added to filter out CEnterline
if(auth()->user()->can('view_projects') && in_array(5, Session::get('user.servicelines'))){
  $types['projects']='Construction projects';
}

if($data['type'] == 'company' && isset($company)){
	$types['company'] = $company->companyname .' locations';
}
$views = array('map'=>'map','list'=>'list');
$values = Config::get('app.search_radius');

?>
<form action="{{route('findme')}}" method = 'post' name="mapselector">
{{csrf_field()}}
<label>Show a</label>
<?php ?>
       <select name='view' class="btn btn-mini" onchange='this.form.submit()'>
          
           @foreach($views as $key=>$field)
				@if($key === $data['view'])
                <option selected value="{{$key}}">{{$key}}</option>
                @else
            
           		<option value="{{$key}}">{{$key}}</option>
				@endif
           @endforeach
        </select>
<label>of</label>
      

       <select name='type' class="btn btn-mini"  onchange='this.form.submit()'>
          
            @foreach($types as $key=>$value)
        				@if($key === $data['type'])
                        <option selected value="{{$key}}">{{$value}}</option>
                        @else
                    
                   		<option value="{{$key}}">{{$value}}</option>
        				@endif
           @endforeach
        </select>
      
<label>within</label>
      
      
       <select name='distance' class="btn btn-mini"  onchange='this.form.submit()'>
           @foreach($values as $value)
           	@if($value === $data['distance'])
            	<option selected value="{{$value}}">{{$value}} miles</option>
                @else
           		<option value="{{$value}}">{{$value}} miles</option>
                @endif
           @endforeach
        </select> of 
        
<input type="text" name="address" 
value="{{str_replace('+',' ', $data['address'])}}"  
id="address" 
style='width:300px'
placeholder='Enter address or check Help Support for auto geocoding' />
<button type="submit"  style="background-color: #4CAF50;"
class= "btn btn-success btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>

{{Form::hidden('company', isset($company) ? $company->id : '' )}}
{{Form::hidden('companyname',isset($company) ? $company->companyname : '')}}
<input type="hidden" name="lng" id ="lng" value="{{$data['lng']}}" />
<input type="hidden" name="lat" id ="lat" value="{{$data['lat']}}" />

</form>
	
		<script>

$("#address").change(function() {
  $('#lat:first').val('');
  $('#lng:first').val('');
});
</script>
