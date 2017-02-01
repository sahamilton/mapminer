@extends ('site.layouts.default')
@section('content')
<div id='content'>
<h1>My Form</h1>
{{Form::open(array('url'=>'/testform'))}}
 <?php 
 $filters = new SearchFilter();
 $tree = $filters->first();
 $keys = Session::get('Search');
 $n= "";

?>
@foreach($tree->getDescendants() as $descendant)

	@if($descendant->type == 'group')
        </fieldset><fieldset><legend>{{{$descendant->filter}}}</legend>
         <?php 
		 $levelName = $descendant->filter;
		 $n=1;?>
       
         </li></ul>
         <ul style="list-style-type: none"> 
        
        @if(isset($keys[0][$descendant->searchtable][$descendant->searchcolumn][$descendant->id])
		or !isset($keys))
            <li><input checked type="checkbox" name="{{{$descendant->id}}}" id="checkAll" value="{{{$descendant->filter}}}">Check All
            @else
                <li><input type="checkbox" name="{{{$descendant->id}}}" id="checkAll" value="{{{$descendant->filter}}}">Check All
            @endif
        
         

 	@else
        @if(isset($n) and $n > $descendant->depth)
        
        	</li></ul>
        @elseif(isset($n) and $n < $descendant->depth)
                <ul style="list-style-type: none">
        @endif
        
       @if(isset($keys[0][$descendant->searchtable][$descendant->searchcolumn][$descendant->id])
		or !isset($keys))
        	<li><input checked type="checkbox"  name="{{{$descendant->id}}}" value="{{{$descendant->filter}}}"/>{{{trim($descendant->filter)}}}
            @else
                <li><input type="checkbox"  name="{{{$descendant->id}}}" value="{{{$descendant->filter}}}"/>{{{trim($descendant->filter)}}}
            @endif
       
     @endif
     
<?php  $n = $descendant->depth;?>

@endforeach
 </li></ul></fieldset>


{{Form::submit()}}


            <div>
{{Form::close()}}
</div></div>

<script>
$(function () {
    
	
	
	$('li :checkbox').on('click', function () {
    var $chk = $(this),
        $li = $chk.closest('li'),
        $ul, $parent;
    if ($li.has('ul')) {
        $li.find(':checkbox').not(this).prop('checked', this.checked)
    }
    do {
        $ul = $li.parent();
        $parent = $ul.siblings(':checkbox');
        if ($chk.is(':checked')) {
            $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
        } else {
            $parent.prop('checked', false)
        }
        $chk = $parent;
        $li = $chk.closest('li');
    } while ($ul.is(':not(.someclass)'));
});
	
	
	$("#save").click(function(){
			var searchdata = $('#selectForm :input').serialize();
			
			$.post("/api/advancedsearch",searchdata,function(response,status){
				window.location.reload(true);});
			
				
			});	
	});	


	</script>
@stop