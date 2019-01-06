@if(! isset($route))
<?php $route = 'company.stateselect';?>
@endif

<form method="post" name="selectForm" action ="{{route($route)}}" >
{{csrf_field()}}
<label>Search for {{$data['company']->companyname}} in </label>
       <select name='state' class="btn btn-mini" onchange='this.form.submit()'>
           
           @foreach ($data['states'] as $state)

           @if(isset($data['statecode']) && $data['statecode'] == $state)
				<option selected value="{{$state}}">{{$state}}</option>
           @else
           		<option value="{{$state}}">{{$state}}</option>
           @endif
				
           @endforeach
        </select>

         <button type="submit"  class= "btn btn-default btn-xs"><i class="fas fa-search" aria-hidden="true"></i> Filter!</button>
<input type="hidden" name='id' value="{{ isset($data['company']->id) ? $data['company']->id : $company[0]->id }}" />
</form>
		
		<script>


</script>
