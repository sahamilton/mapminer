<?php 
  $values = Config::get('app.search_radius');
  $default = 25;
  $number = [5,10,25,100];
  $count='5';
  ?>

<form method ="post"  action ="{{route('lead.find')}}" name="leadaddress">
{{csrf_field()}}
<select name='number' class="btn btn-mini" >
           @foreach($number as $value)
              @if($value == $count)
              <option selected value="{{$value}}">{{$value}} closest within</option>
              @else
              <option value="{{$value}}">{{$value}} closest within</option>
              @endif
           @endforeach
        </select> 
 within 
 <select name='distance' class="btn btn-mini" >
           @foreach($values as $value)
              @if($value == $default)
              <option selected value="{{$value}}">{{$value}} miles</option>
              @else
              <option value="{{$value}}">{{$value}} miles</option>
              @endif
           @endforeach
        </select> <label> of address</label>
<input type="text" id="address" name="address" value="{{isset($data['address']) ? $data['address'] : ''}}"/>
<input type="hidden" name="lat" id="lat" value=""/>
<input type="hidden" name="lng" id="lat" value=""/>
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>

</form>

