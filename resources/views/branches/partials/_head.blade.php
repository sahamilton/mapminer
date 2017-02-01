<div class="pull-right">
	 <p><a href="{{ URL::to('branch') }}">Show all branches</a></p>	
		</div>
        <h1>{{$data['title']}}</h1>
       {{$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''}}
       <h4> within 10 miles of the {{ucwords(strtolower($data['branch']->branchname))}} branch # {{$data['branch']->branchnumber}} </h4>
        <h4>Address:</h4>
        <p>{{$data['branch']->street}}{{$data['branch']->address2}}<br/>
        {{$data['branch']->city}},{{$data['branch']->state}} {{$data['branch']->zip}}<br />
        {{$data['branch']->phone}}</p>
       
        <p>Branch managed by
        @if(isset($data['manager']->id)) 
         <a title = "Email {{$data['manager']->firstname}}" 
         href = "{{route('person.show',$data['manager']->id)}}">{{$data['manager']->firstname}} {{$data['manager']->lastname}}</a>
        @endif
        </p>
        <p>Branch serviced by <a title="View {{$data['branch']->branchname}} branch sales team" href ="{{route('showlist/salesteam',$data['branch']->id)}}">{{count($data['branch']['servicedBy'])}} sales reps.</a></p>
        
        <p>Branch service radius: {{$data['branch']->radius}} miles.</p>
        <h4>Service Lines:</h4>
        <ul style="margin-left:40px">
            @foreach($data['branch']->servicelines as $serviceline)
               <li>  {{$serviceline->ServiceLine}} </li>
            @endforeach
</ul>            
<?php $data['address'] = $data['branch']->street ." ".$data['branch']->city ." ".$data['branch']->state;
$data['lat'] = $data['branch']->lat;
$data['lng'] = $data['branch']->lng;


?>

@if($type=='map')
<p><a href="{{URL::to('branch/'.$data['branch']->id.'/showlist')}}"><i class="glyphicon glyphicon-th-list"></i> List view</a></p>
@else
<p><a href="{{URL::to('branch/'.$data['branch']->id)}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a></p>
@endif
<div>
@include('partials/advancedsearch')
</div>
@include('maps/partials/_form')


