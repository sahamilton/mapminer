@extends('admin.layouts.default')
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
<<<<<<< HEAD
<p><i class="fa fa-trash" aria-hidden="true"></i> = Delete | <i class="fa fa-ban" aria-hidden="true"></i> = Inactive | <i class="fa fa-home" aria-hidden="true"></i> = Applies to Accounts  | <i class="fa fa-flag" aria-hidden="true"></i> = Applies to locations  | <i class="fa fa-arrow-up" aria-hidden="true"></i> <i class="fa fa-arrow-down" aria-hidden="true"></i>= Resequence</p>
<p>@include('partials.advancedsearch')</p>
	@if (Auth::user()->hasRole('Admin'))
=======
<p><i class="far fa-trash" aria-hidden="true"></i> = Delete | <i class="far fa-ban" aria-hidden="true"></i> = Inactive | <i class="far fa-home" aria-hidden="true"></i> = Applies to Accounts  | <i class="far fa-flag" aria-hidden="true"></i> = Applies to locations  | <i class="far fa-arrow-up" aria-hidden="true"></i> <i class="far fa-arrow-down" aria-hidden="true"></i>= Resequence</p>
<p>@include('partials.advancedsearch')</p>
	@if (auth()->user()->hasRole('Admin'))
>>>>>>> development
        <div class="pull-right">
            <a href="{{{ route('searchfilters.create') }}}" 
            class="btn btn-small btn-info iframe">
            
<<<<<<< HEAD
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
=======
<i class="fas fa-plus-circle " aria-hidden="true"></i>
>>>>>>> development
 Create New Filter</a>
        </div>
	@endif
    
    @foreach($tree->getDescendants() as $descendant)
		
        @if($descendant->depth == 1)
<<<<<<< HEAD
            </fieldset><fieldset><legend><a data-href="{{route('searchfilters.destroy',$descendant->id)}}" style="color:red"  data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter group and all its 'children'" href="#"title="Remove {{$descendant->filter}} filter group"><i class="fa fa-trash" aria-hidden="true"></i></a>  
=======
            </fieldset><fieldset><legend><a data-href="{{route('searchfilters.destroy',$descendant->id)}}" style="color:red"  data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter group and all its 'children'" href="#"title="Remove {{$descendant->filter}} filter group"><i class="far fa-trash" aria-hidden="true"></i></a>  
>>>>>>> development
            
            <a href="{{route('searchfilters.edit',$descendant->id)}}"
            title="Edit {{$descendant->filter}} filter">{{{$descendant->filter}}}</a>
            
            </legend>
        @else
        	
            <div class='level{{$descendant->depth}}'>
             @if($descendant->inactive== 1)
<<<<<<< HEAD
             <i class="fa fa-ban" aria-hidden="true"></i>
             @elseif ($descendant->searchtable == 'companies')
            <i class="fa fa-home" aria-hidden="true"></i>
           @else
            <i class="fa fa-flag" aria-hidden="true"></i>
           @endif
            <a data-href="{{route('searchfilters.destroy',$descendant->id)}}" style="color:red" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter and all its 'children'" href="#" title="Remove {{$descendant->filter}} filter"><i class="fa fa-trash" aria-hidden="true"></i></a>
=======
             <i class="far fa-ban" aria-hidden="true"></i>
             @elseif ($descendant->searchtable == 'companies')
            <i class="far fa-home" aria-hidden="true"></i>
           @else
            <i class="far fa-flag" aria-hidden="true"></i>
           @endif
            <a data-href="{{route('searchfilters.destroy',$descendant->id)}}" style="color:red" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$descendant->filter}} filter and all its 'children'" href="#" title="Remove {{$descendant->filter}} filter"><i class="far fa-trash" aria-hidden="true"></i></a>
>>>>>>> development
            <a href="{{route('searchfilters.edit',$descendant->id)}}"
            title="Edit {{$descendant->filter}} filter">{{{$descendant->filter}}}</a>
            ( {{$descendant->id}})
            
            @if($descendant->lft != $descendant->getSiblingsAndSelf()->min('lft'))
            	<a href="{{route('admin.searchfilter.promote',$descendant->id)}}"
<<<<<<< HEAD
                title="Move {{$descendant->filter}} filter up"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
=======
                title="Move {{$descendant->filter}} filter up"><i class="far fa-arrow-up" aria-hidden="true"></i></a>
>>>>>>> development
          	@endif
          	@if( $descendant->rgt != $descendant->getSiblingsAndSelf()->max('rgt'))
           
             <a href="{{route('admin.searchfilter.demote',$descendant->id)}}"
             title="Move {{$descendant->filter}} filter down">
<<<<<<< HEAD
             <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
=======
             <i class="far fa-arrow-down" aria-hidden="true"></i></a>
>>>>>>> development
            
            @endif
           @if($descendant->color != '')
            <img src='//chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|{{$descendant->color}}' />
           @else
            <img src='//chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|eeeeee' />
           @endif
          
			<?php $n[$descendant->depth]['rgt'] = $descendant->rgt;?>
            
           
            </div>
        @endif
    @endforeach
</fieldset>
</div>
@include('partials/_scripts')
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
