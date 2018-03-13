<?php 
  $values = Config::get('app.search_radius');
  $default = 100;
  $number = [5,10,25,100];
  $count='5';
  ?>

<form class="form-inline" method ="post"  action ="{{route('lead.find')}}" name="leadaddress">
{{csrf_field()}}
<select id="selectnumber" name='number' class="btn btn-mini" >
           @foreach($number as $value)
              @if($value == $count)
              <option selected value="{{$value}}">{{$value}} </option>
              @else
              <option value="{{$value}}">{{$value}} </option>
              @endif
           @endforeach
        </select> 
 <label>closest within </label>
 
 <select id="selectdistance" name='distance' class="btn btn-mini" >
           @foreach($values as $value)
              @if($value == $default)
              <option selected value="{{$value}}">{{$value}} miles</option>
              @else
              <option value="{{$value}}">{{$value}} miles</option>
              @endif
           @endforeach
        </select> 
        <label> of address</label>
<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
<input
class="form-control{{ $errors->has('address') ? ' has-error' : ''}}"  
type="text" 
id="address" 
name="address" 
value="{{isset($data['address']) ? $data['address'] : ''}}"
required
/>
{!! $errors->first('address', '<p class="help-block">:message</p>') !!}
</div>
<input type="hidden" name="lat" id="lat" value=""/>
<input type="hidden" name="lng" id="lat" value=""/>
    <button type="submit"  class= "btn btn-success btn-xs">
      <span class="glyphicon glyphicon-search"></span>
       Search!</button>
</form>
<?php $action = route('lead.find');?>
@include('partials._noaddressmodal')
<script>


$("select[id^='select']").change(function() {
  if($.trim($('#address').val()) == ''){
    $( "#noaddress" ).modal('show');
    
  }else{
    
    this.form.submit();
}
});

</script>
