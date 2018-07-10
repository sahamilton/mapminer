

<form method="post" id="selectForm" action ="{{route($route)}}" >
{{csrf_field()}}


<label>Search for branches in </label>
       <select name='state' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach ($allstates as $state)

           @if(isset($state) && $state->statecode == $state->state)
				<option selected value="{{$state->state}}">{{$state->state}}</option>
           @else
           		<option value="{{$state->state}}">{{$state->state}}</option>
           @endif
				
           @endforeach
        </select>
 
         <button type="submit"  class= "btn btn-default btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Search!</button>


        {{Form::close()}}
		
		<script>


</script>
</form>
