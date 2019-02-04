<div class="row">
   

@if(! isset($data) or $data['type'] == 'branch' && isset($servicelines))
  @foreach ($servicelines as $serviceline)
    
    {{str_replace("PeopleReady: ","",$serviceline->ServiceLine)}} = &nbsp; <img src='{{asset('geocoding/markers/'.$serviceline->color.'-pin.png')}}' />&nbsp&nbsp&nbsp


  @endforeach
@elseif($data['type'] == 'location')

  @php 
    $addressKeys = [

    'customer'=>'red',
    'project'=>'darkgreen',
    'location'=>'blue',
    'lead'=>'yellow',
    ];
  @endphp
  @foreach ($addressKeys as $key=>$color)

    <input type="checkbox" 
    {{session()->has('geo.addressType') && in_array($key,session('geo.addressType')) ? 'checked' : ''}}  
 
    name="addressType[]" value="{{$key}}" />
    {{ucwords($key)}}&nbsp = &nbsp <img src='{{asset('geocoding/markers/'.$color.'-pin.png')}}' />&nbsp&nbsp&nbsp

  @endforeach





@endif
</div>