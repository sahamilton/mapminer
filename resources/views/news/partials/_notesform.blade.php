<!-- Title -->

<div class="form-group">
{{Form::label('title','Title:',array('class'=>'col-sm-2 control-label'))}}
<div class="col-sm-10">
{{Form::text('title',isset($note[0]->title) ? $note[0]->title: '',array('class'=>"form-control"))}}
<span class='error'>{{$errors->first('title')}}</span>
</div></div>

<!-- Content -->

<div class="form-group">
{{Form::label('content','Note:',array('class'=>'col-sm-2 control-label'))}}
<div class="col-sm-10">
{{Form::textarea('content',isset($note[0]->content) ? $note[0]->content: '',array('class'=>"form-control"))}}
<span class='error'>{{$errors->first('content')}}</span>
</div></div>