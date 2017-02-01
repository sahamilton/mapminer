
{{Form::open(array('route'=>'company.stateselect','class'=>'form', 'id'=>'selectForm'))}}

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
{{ Form::hidden('id', isset($company->id) ? $company->id : $company[0]->id) }}

        {{Form::close()}}
		
		<script>


</script>
