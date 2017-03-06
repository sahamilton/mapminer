<?php
$statelist = App\Branch::distinct()->orderBy('state')->get(array('state'));
		foreach ($statelist as $state) {
			$states[]= $state->state;
			
		}
?>

{{Form::open(array('route'=>$route,'class'=>'form', 'id'=>'selectForm'))}}

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
 
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>


        {{Form::close()}}
		
		<script>


</script>
