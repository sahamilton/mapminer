@extends('admin.layouts.default')
@section('content')

<h2>All How To Fields</h2>
<div class="float-right">
<a href="{{{ route('howtofields.create') }}}" 
class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Field</a>
</div>

<div class="dd" id="nestable">
    <ol class="dd-list">
        @foreach ($howtofields->where('depth', 1) as $field)
        
        <li class="dd-item" data-id="{{$field->id}}">
            <div class="dd-handle">{{$field->fieldname}}</div>
           
                <ol class="dd-list">
                    @foreach ($field->immediateDescendants()->get() as $subField)
                    
                    <li class="dd-item" data-id="{{$subField->id}}">
                        <div class="dd-handle">{{$subField->fieldname}}</div>
                    </li>
                    @endforeach
                </ol>
           
        </li>
        
        
        @endforeach
    </ol>
</div>

<script type="text/javascript" 
src="/js/nestable.js"></script>   
<script>
    $(document).ready(function ()
    {

        var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {

            var data =JSON.stringify(list.nestable('serialize'));
       
             $.ajax({
                type: 'GET',
                url: "{{route('howtofields.reorder')}}",
                dataType: 'json',
                data: {id: data, api_token:"{{auth()->user()->api_token}}"},
                success: function(msg) {
                  alert(msg);
                }
              });
            //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);

   
    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));


    });
    

</script>

@endsection
