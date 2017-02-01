<form method = 'post' action ="{{route('company.filter')}}">
<?php $selectors=['locations'=>'with Locations','nolocations'=>'without Locations','both'=>'Both'];
?>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

<div class="form-group">
{{Form::label($locationFilter,'Select Companies:',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">

{{Form::select("locationFilter",$selectors,$locationFilter,array('id'=>"selectManager",'onchange' => 'this.form.submit()'),$locationFilter)}}
</div></div>



</form>