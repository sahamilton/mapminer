@if($data['limited'])
<div style="background-color:yellow"><p>There are <strong>{{number_format($data['count'],0)}}</strong> 
@if($data['segment'] and $data['segment'] != 'All'){{$data['segment']}} @endif locations for  {{$company->companyname}}. Please select a state to narrow your search</p>
   <p>Here are the {{$data['limited']}} within 200 miles of your location</p>
   </div>
 @endif
