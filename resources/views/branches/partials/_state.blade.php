<?php
$statelist = App\Branch::distinct()->orderBy('state')->get(array('state'));
		foreach ($statelist as $state) {
			$states[]= $state->state;
			
		}
?>

<form method="post" id="selectForm" action ="{{route($route)}}" >
{{csrf_field()}}


<label>Search for branches in </label>
       <select name='state' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach ($states as $state)
           @if(isset($data['state']) && $data['state'] == $state)
				<option selected value="{{$state}}">{{$state}}</option>
           @else
           		<option value="{{$state}}">{{$state}}</option>
           @endif
				
           @endforeach
        </select>
 
         <button type="submit"  class= "btn btn-default btn-xs"><i class="far fa-search" aria-hidden="true"></i> Search!</button>


        {{Form::close()}}
		
		<script>


</script>
</form>
