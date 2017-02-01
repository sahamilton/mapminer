@extends('site/layouts/default')
@section('content')

<style>
.level1{margin-left:20px;}
.level2{margin-left:40px;}
.level3{margin-left:60px;}
</style>
<h1>Advanced Search Filters</h1>

    <?php $n = '';?>
@foreach($tree->getDescendants() as $descendant)
	@if($descendant->depth == 1)
        </fieldset><fieldset><legend>{{{$descendant->filter}}}</legend>
         <?php $level = $descendant->filter;?>
 	@else
        @if($descendant->type=='one')
       	 <input type="radio" name="{{$level}}"  />{{{$descendant->filter}}}
        @else
            @if ($n =='')
            <ul style="list-style-type: none">
                <li><input checked type="checkbox" name="checkAll" id="checkAll">Check All
                <?php $n++;?>
            @endif
            @if($level > $descendant->depth)
                </li></ul>
            @elseif($level < $descendant->depth)
                <ul style="list-style-type: none">
            @else
            
            @endif
        <?php  $level = $descendant->depth;?>
        <li><input checked type="checkbox" class='level{{$descendant->depth}}' name="{{{$descendant->filter}}}[]" />{{{$descendant->filter}}}
     @endif
    @endif

@endforeach
</ul>

@include('partials/_scripts')
<script>
$(function () {
    $("input[type='checkbox']").change(function () {
        $(this).siblings('ul')
            .find("input[type='checkbox']")
            .prop('checked', this.checked);
    });
	
	
	
	
	
	$("#save").click(function(){
			var searchdata = $('#selectForm :input').serialize();
			
			$.post("/api/advancedsearch",searchdata,function(response,status){
				window.location.reload(true);});
			
				
			});	
	});	


	</script>
@stop