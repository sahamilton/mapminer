<legend>Industry Verticals</legend>
    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="vertical">Industry Verticals</label>
        <div class="input-group input-group-lg ">


@foreach($verticals as $descendant)

	@if($descendant->type == 'group'  )
		@if(! $loop->first)
        	</fieldset>
        @endif
        <fieldset>
	        <?php $levelName = $descendant->filter;
			 $n=1;?>
      	 @if(! $loop->first)
         	</li></ul>
         @endif
         <ul style="list-style-type: none"> 
      		<li><input type="checkbox" name="parent[]" id="checkAll" value="{{{$descendant->id}}}">
      		Check All {{$descendant->filter}}  		
	@else
		@if(isset($n) && $n > $descendant->depth && !$loop->first)
</li></ul>
		@elseif(isset($n) and $n < $descendant->depth)
			<ul style="list-style-type: none">
		@endif
<li>
			@if((is_array(old('vertical')) && in_array($descendant->id,old('vertical'))) 
			or (isset($news->relatedIndustries) && $news->relatedIndustries->contains('id',$descendant->id)))

				<input type="checkbox" checked name="vertical[]" value="{{{$descendant->id}}}"/>{{$descendant->filter}}
			@else
				<input type="checkbox"  name="vertical[]" value="{{{$descendant->id}}}"/>
				{{$descendant->filter}}
			@endif
		@endif
<?php  $n = $descendant->depth;?>
@endforeach
</li></ul></fieldset>
            @include('partials._verticals')  
            <span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">
                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
        </div>
    </div>