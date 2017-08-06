<?php 
// Default values

$session = Session::get('geo');
if(! isset($session)) {
	$session = array('type'=>'accounts','distance'=>'10','address'=>NULL,'view'=>'maps','lat'=>'39.8282','lng'=>'-98.5795');
}
foreach($session as $key=>$value)
{
	if(!isset($data[$key])){
		$data[$key] = $value;
	}
}
$types = ['location'=>'All accounts','branch'=>'Branches'];
if(auth()->user()->can('view_projects')){
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
{{Form::label('type','Show a')}}
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
  {{Form::label('view','of')}} 
      

       <select name='type' class="btn btn-mini"  onchange='this.form.submit()'>
          
            @foreach($types as $key=>$value)
        				@if($key === $data['type'])
                        <option selected value="{{$key}}">{{$value}}</option>
                        @else
                    
                   		<option value="{{$key}}">{{$value}}</option>
        				@endif
           @endforeach
        </select>
      
      {{Form::label('distance','within')}}
      
      
       <select name='distance' class="btn btn-mini"  onchange='this.form.submit()'>
           @foreach($values as $value)
           	@if($value === $data['distance'])
            	<option selected value="{{$value}}">{{$value}} miles</option>
                @else
           		<option value="{{$value}}">{{$value}} miles</option>
                @endif
           @endforeach
        </select> of 
        
{{Form::text('address',str_replace("+", " ", $data['address']),$attributes = array( 'id'=>'address','style'=>'width:300px'))}}
        
       
        </label>
        

       
       

      
      
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>
{{Form::hidden('lat',$data['lat'],$attributes = array( 'id'=>'lat'))}}
{{Form::hidden('company', isset($company) ? $company->id : '' )}}
{{Form::hidden('companyname',isset($company) ? $company->companyname : '')}}

{{Form::hidden('lng',$data['lng'],$attributes = array( 'id'=>'lng'))}} 


        {{Form::close()}}
	
		<script>

$("#address").change(function() {
  $('#lat:first').val('');
  $('#lng:first').val('');
});
</script>
