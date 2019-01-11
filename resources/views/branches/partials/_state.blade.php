
@php
$statelist = App\Branch::distinct()->orderBy('state')->get(array('state'));
		foreach ($statelist as $state) {
			$states[]= $state->state;
			
		}
@endphp

<form method="post" id="selectForm" action ="{{route($route)}}" >
@csrf


<label>Search for branches in </label>
       <select name='state' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach ($allstates as $statecode)

           @if(isset($state) && $statecode->statecode == $state->state)
				<option selected value="{{$statecode->statecode}}">{{$statecode->statecode}}</option>
           @else
           		<option value="{{$statecode->statecode}}">{{$statecode->statecode}}</option>
           @endif
				
           @endforeach
        </select>
 

         <button type="submit"  class= "btn btn-default btn-xs"><i class="fas fa-search" aria-hidden="true"></i> Search!</button>

        </form>
		
		<script>


</script>
</form>
