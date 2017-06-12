<form method = 'post' action ="{{route('company.filter')}}">
<?php $selectors=['locations'=>'with Locations','nolocations'=>'without Locations','both'=>'Both'];
?>
{{csrf_field()}}

<div class="form-group">
{{Form::label($locationFilter,'Select Companies:',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">
<select name='locationFilter' onchange="this.form.submit()">
@foreach ($selectors as $key=>$value)
<option {{$locationFilter == $key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
@endforeach
</select>

</div></div>



</form>