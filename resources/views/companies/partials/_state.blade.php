<form method="post" name="selectForm" action ="{{route('company.stateselect')}}" >
{{csrf_field()}}
<label>Search for {{$company->companyname}} in </label>
       <select name='state' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach ($states as $state)
           @if(isset($data['statecode']) && $data['statecode'] == $state)
				<option selected value="{{$state}}">{{$state}}</option>
           @else
           		<option value="{{$state}}">{{$state}}</option>
           @endif
				
           @endforeach
        </select>
 
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>
<input type="hidden" name='id' value="{{ isset($company->id) ? $company->id : $company[0]->id }}" />
</form>
		
		<script>


</script>
