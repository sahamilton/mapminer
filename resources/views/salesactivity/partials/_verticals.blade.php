<?php 
	$filters = new App\SearchFilter();
	
	$tree = $filters->first();
?>

<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
<label for="vertical">Vertical</label>
<div class="input-group input-group-lg ">

@foreach($tree->getDescendants()->where('searchtable','=','companies')->where('inactive','=',0) as $descendant)

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
			<li><input type="checkbox"  name="vertical[]" value="{{{$descendant->id}}}"/>
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
</div></div>

