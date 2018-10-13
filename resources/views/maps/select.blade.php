<?php $distance =  Config::get('app.search_radius');;
	?>
{{Form::open(array('route'=>'geo.find','class'=>'form', 'id'=>'selectForm'))}}
Show a
 <?php $views = array('map'=>'map','list'=>'list');?>
       <select name='type' class="btn btn-mini" onchange='this.form.submit()'>
          
            @foreach($views as $key=>$value)
				@if($key === 'list')
                <option selected value="{{$key}}">{{$value}}</option>
                @else
            
           		<option value="{{$key}}">{{$value}}</option>
				@endif
           @endforeach
        </select>
        of <?php $views = array('location'=>'accounts','branch'=>'branches');?>
       <select name='view' class="btn btn-mini"  onchange='this.form.submit()'>
             @foreach($views as $key=>$value)
           		<option selected value="{{$key}}">{{$value}}</option>
           @endforeach
        </select>
         
 within 
 	<select name='distance' class="btn btn-mini"  onchange='this.form.submit()'>
           @foreach($distance as $value)
           
           		<option selected value="{{$value}}">{{$value}} miles</option>
              
           @endforeach
        </select> of miles of 


{{Form::text('address',$data['address'],$attributes = array( 'id'=>'address','style'=>'width:300px'))}}


{{Form::hidden('lat',$data['lat'],$attributes = array( 'id'=>'lat'))}}

{{Form::hidden('lng',$data['lng'],$attributes = array( 'id'=>'lng'))}}

<button type="submit"  class= "btn btn-default btn-xs"><i class="fas fa-search" aria-hidden="true"></i> Search</button>

{{Form::close()}}
<div id="showMe"></div>
<script>

$("#address").change(function() {
  $('#lat:first').val('');
  $('#lng:first').val('');
});
</script>
