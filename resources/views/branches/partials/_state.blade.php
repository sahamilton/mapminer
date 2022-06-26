
@php

$statelist = App\State::has('branches')->orderBy('statecode')->pluck('fullstate', 'statecode')->toArray();
$add['All'] = 'All Branches';
$statelist = array_merge($add , $statelist);
asort($statelist);
@ray($statelist)

@endphp

<form class = "form-inline" 
	method="post" action="{{route('branches.statemap')}}" 
	role = "form">

	@csrf
     <div class = "form-group">
        
        <x-form-select name="state" :options='$statelist' label="Branches in:" />
        
     </div>
     
     <button type = "submit" class = "btn btn-success">Submit</button>
</form>