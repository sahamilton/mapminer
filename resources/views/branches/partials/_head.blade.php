<div class="pull-right">
	 <p><a href="{{ route('branches.index') }}">Show all branches</a></p>	
		</div>
        <h1>{{$data['title']}}</h1>

       <h4> within 10 miles of the {{ucwords(strtolower($data['branch']->branchname))}} branch # {{$data['branch']->branchnumber}} </h4>
        <h4>Address:</h4> 
        <p>{{$data['branch']->street}}{{$data['branch']->address2}}<br/>
        {{$data['branch']->city}},{{$data['branch']->state}} {{$data['branch']->zip}}<br />
        {{$data['branch']->phone}}</p>
        <h4>Branch Team</h4>
        @foreach ($data['branch']->relatedPeople()->get() as $people)

        <p><strong>{{$roles[$people->pivot->role_id]}}</strong>: {{$people->postName()}} </p>

        @endforeach

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
<p><a href="{{route('showlist.locations',$data['branch']->id)}}"><i class="glyphicon glyphicon-th-list"></i> List view</a></p>
@else
<p><a href="{{route('branches.show',$data['branch']->id)}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a></p>
@endif
<div>
@include('partials/advancedsearch')
</div>
@include('maps/partials/_form')


