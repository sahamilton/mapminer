@if($limited)
<div style="background-color:yellow"><p>There are <strong>{{number_format($count,0)}}</strong> 
@if($data['segment']!='All'){{$data['segment']}} @endif locations for  {{$company->companyname}}. Please select a state to narrow your search</p>
   <p>Here are the {{$limited}} within {{$distance}} miles of your location</p>
   </div>
 @endif