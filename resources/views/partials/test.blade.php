 @extends ('site.layouts.default')
@section('content')
<div id='content'><a href="#" data-toggle="modal"
   data-target="#advancedSearch">Advanced Search Options</a>
 <?php 
	$filters = new SearchFilter();

	$filters->setSearch(); 
	$keys = Session::get('Search');
	

 $tree = $filters->first();
 

?>
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Advanced Search Options</h4>
            </div>
            <div id='form' class="modal-body">
               
                <form id ='filterForm' method='post'>

@foreach($tree->getDescendants() as $descendant)

	@if($descendant->type == 'group')
        </fieldset><fieldset><legend>{{{$descendant->filter}}}</legend>         <?php 
		 $levelName = $descendant->filter;
		 $n=1;?>
       
         </li></ul>
         <ul style="list-style-type: none"> 
       
       
        @if((isset($keys[0][$descendant->searchtable][$descendant->searchcolumn]) 
		and in_array($descendant->id,$keys[0][$descendant->searchtable][$descendant->searchcolumn])) 
		or !isset($keys))
   
            <li><input checked type="checkbox" name="{{{$descendant->id}}}" id="checkAll" value="{{{$descendant->filter}}}">Check All {{{$descendant->filter}}}
            @else
                <li><input type="checkbox" name="{{{$descendant->id}}}" id="checkAll" value="{{{$descendant->filter}}}">Check All {{{$descendant->filter}}}
            @endif
        
         

 	@else
        @if(isset($n) and $n > $descendant->depth)
        
        	</li></ul>
        @elseif(isset($n) and $n < $descendant->depth)
                <ul style="list-style-type: none">
        @endif
        
       @if((isset($keys[0][$descendant->searchtable][$descendant->searchcolumn]) 
		and in_array($descendant->id,$keys[0][$descendant->searchtable][$descendant->searchcolumn])) 
		or !isset($keys))
        	<li><input checked type="checkbox"  name="{{{$descendant->id}}}" value="{{{$descendant->filter}}}"/>{{{trim($descendant->filter)}}}
            @else
                <li><input type="checkbox"  name="{{{$descendant->id}}}" value="{{{$descendant->filter}}}"/>{{{trim($descendant->filter)}}}
            @endif
       
     @endif
     
<?php  $n = $descendant->depth;?>

@endforeach
 </li></ul></fieldset>


</div>

            <div class="modal-footer">
                <!--- <button type='button' id='searchsave' type="button" value='Save' class="btn btn-primary" >Save</button>--> 
                {{Form::submit()}}
        </div>   </form> 

            </div>

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
	
	
	$("#searchsave").click(function(){
			var searchdata = $('#filterForm :input').serialize();
			
			$.post("/api/advancedsearch",searchdata,function(response,status){
				window.location.reload(true);});
			
				
			});	
	});	


	</script>
<<<<<<< HEAD
    @stop
=======
    @endsection
>>>>>>> development

