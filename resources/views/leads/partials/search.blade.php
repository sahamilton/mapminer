<?php 
  $values = Config::get('app.search_radius');
  $default = 25;
  ?>

<form method ="post"  action ="{{route('lead.find')}}" name="leadaddress">
{{csrf_field()}}

<label>Search Address</label>
       <input type="text" name="address" /> within 
 <select name='distance' class="btn btn-mini" >
           @foreach($values as $value)
              @if($value == $default)
              <option selected value="{{$value}}">{{$value}} miles</option>
              @else
              <option value="{{$value}}">{{$value}} miles</option>
              @endif
           @endforeach
        </select> 
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>

</form>