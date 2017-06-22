

@foreach($verticals as $descendant)

	@if($descendant->type == 'group')
        </fieldset><fieldset><legend>{{{$descendant->filter}}}</legend>         <?php 
		 $levelName = $descendant->filter;
		 $n=1;?>
       
         </li></ul>
         <ul style="list-style-type: none"> 
      
           
                    <li><input type="checkbox" name="parent[]" id="checkAll" value="{{{$descendant->id}}}">Check All {{{$descendant->filter}}}
            
  		
	@else
		@if(isset($n) and $n > $descendant->depth)

			</li></ul>
		@elseif(isset($n) and $n < $descendant->depth)
			<ul style="list-style-type: none">
		@endif
		
		@if($descendant->isLeaf())
			<li><input type="checkbox"  {{isset($activity) && in_array($descendant->id, $activity->vertical->pluck('id')->toArray()) ? 'checked': ''}} name="vertical[]" value="{{{$descendant->id}}}"/>
			{{{trim($descendant->filter)}}}
		@else
			<li><input type="checkbox"  name="parent[]" value="{{{$descendant->id}}}"/>
			{{{trim($descendant->filter)}}}
		@endif

@endif
     
<?php  $n = $descendant->depth;?>

@endforeach
 </li></ul></fieldset>

{!! $errors->first('vertical', '<p class="help-block">:message</p>') !!}


