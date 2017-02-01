@extends('site/layouts/default')
@section('content')

<style>
.level1{margin-left:20px;}
.level2{margin-left:40px;}
.level3{margin-left:60px;}
.level4{margin-left:80px;}
</style>
@include('partials/_modal')
<?php $n = array();?>
<h1>Search Filters</h1>
<div>
<h4>Key</h4>
<p><span class="glyphicon glyphicon-trash"></span> = Delete | <span class="glyphicon glyphicon-ban-circle"></span> = Inactive | <span class="glyphicon glyphicon-home"></span> = Applies to Accounts  | <span class="glyphicon glyphicon-flag"></span> = Applies to locations  | <span class="glyphicon glyphicon-arrow-up"></span> <span class="glyphicon glyphicon-arrow-down"></span>= Resequence</p>
	@if (Auth::user()->hasRole('Admin'))
        <div class="pull-right">
            <a href="{{{ route('admin.searchfilters.create') }}}" 
            class="btn btn-small btn-info iframe">
            <span class="glyphicon glyphicon-plus-sign"></span> Create New Filter</a>
        </div>
	@endif
    
    @foreach($tree->getDescendants() as $descendant)
		
        @if($descendant->depth == 1)
            </fieldset><fieldset><legend><a data-href="{{route('admin.searchfilter.delete',$descendant->id)}}" style="color:red"  data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter group and all its 'children'" href="#"title="Remove {{$descendant->filter}} filter group"><span class="glyphicon glyphicon-trash"> </span></a>  
            
            <a href="{{route('admin.searchfilters.edit',$descendant->id)}}"
            title="Edit {{$descendant->filter}} filter">{{{$descendant->filter}}}</a>
            
            </legend>
        @else
        	
            <div class='level{{$descendant->depth}}'>
             @if($descendant->inactive== 1)
             <span class="glyphicon glyphicon-ban-circle"></span>
             @elseif ($descendant->searchtable == 'companies')
            <span class="glyphicon glyphicon-home"></span>
           @else
            <span class="glyphicon glyphicon-flag"></span>
           @endif
            <a data-href="{{route('admin.searchfilter.delete',$descendant->id)}}" style="color:red" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter and all its 'children'" href="#" title="Remove {{$descendant->filter}} filter"><span class="glyphicon glyphicon-trash"> </span></a>
            <a href="{{route('admin.searchfilters.edit',$descendant->id)}}"
            title="Edit {{$descendant->filter}} filter">{{{$descendant->filter}}}</a>
            ( {{$descendant->id}})
            
            @if($descendant->lft != $descendant->getSiblingsAndSelf()->min('lft'))
            	<a href="{{route('admin.searchfilter.promote',$descendant->id)}}"
                title="Move {{$descendant->filter}} filter up"><span class="glyphicon glyphicon-arrow-up"></span></a>
          	@endif
          	@if( $descendant->rgt != $descendant->getSiblingsAndSelf()->max('rgt'))
           
             <a href="{{route('admin.searchfilter.demote',$descendant->id)}}"
             title="Move {{$descendant->filter}} filter down">
             <span class="glyphicon glyphicon-arrow-down"></span></a>
            
            @endif
           @if($descendant->color != '')
            <img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|{{$descendant->color}}' />
           @else
            <img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|eeeeee' />
           @endif
          
			<?php $n[$descendant->depth]['rgt'] = $descendant->rgt;?>
            
           
            </div>
        @endif
    @endforeach
</fieldset>
</div>
@include('partials/_scripts')
@stop