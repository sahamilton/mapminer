@extends('admin.layouts.default')
@section('content')
<style>
.level1{margin-left:20px;}
.level2{margin-left:40px;}
.level3{margin-left:60px;}
.level4{margin-left:80px;}
</style>
<h1>All How To Fields</h1>

<div class="float-right">
<a href="{{{ route('howtofields.create') }}}" 
class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Field</a>
</div>
<ul id="sortable">
    @foreach($howtofields as $descendant)
        <li>
        @if($descendant->depth == 1)
            <a 
                    
                data-href="" 
                style="color:red"  
                data-toggle="modal" 
                data-target="#confirm-delete" 
                data-title = "{{$descendant->fieldname}} group and all its 'children'" 
                href="#"

                title="Remove {{$descendant->fieldname}} group">
                <i class="fa fa-trash" aria-hidden="true"></i>
            </a>  

            
            <a 
            class="sortable"
            href=""
            title="Edit {{$descendant->fieldname}} ">{{{$descendant->fieldname}}}</a>
            

        @else
            
            <div class='level{{$descendant->depth}} sortable'>
             @if($descendant->active!= 1)

             <i class="fa fa-ban" aria-hidden="true"></i>
            
           @else
            <i class="fa fa-flag text-success" aria-hidden="true"></i>
           @endif
            <a 
                data-href="" 
                style="color:red" 
                data-toggle="modal" 
                data-target="#confirm-delete" 
                data-title = "{{$descendant->fieldname}} tab and all its 'children'" 
                href="#" 
                title="Remove {{$descendant->fieldname}} tab">
                <i class="fa fa-trash" aria-hidden="true"></i>
            </a>
            <a href=""
            title="Edit {{$descendant->fieldname}} field">{{{$descendant->fieldname}}}</a>
            ( {{$descendant->id}})
            
            @if($descendant->lft != $descendant->getSiblingsAndSelf()->min('lft'))
                <a href=""

                title="Move {{$descendant->fieldname}} field up">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
            </a>

            @endif
            @if( $descendant->rgt != $descendant->getSiblingsAndSelf()->max('rgt'))
           
             <a href=""
                 title="Move fieldname down">

                 <i class="fa fa-arrow-down" aria-hidden="true"></i>
             </a>

            
            @endif
           
          
            @php $n[$descendant->depth]['rgt'] = $descendant->rgt;@endphp
            
           
            </li>
        @endif
    @endforeach
</ul>
@include('partials._modal')
@include('partials/_scripts')
<script>
$('#element').sortable({
    axis: 'y',
    update: function (event, ui) {
        var data = $(this).sortable('serialize');

        // POST to server using $.post or $.ajax
        $.ajax({
            data: data,
            type: 'POST',
            url: '/your/url/here'
        });
    }
});
</script>
@endsection
