@extends('admin.layouts.default')
@section('content')
@if(! isset($data['route']))
<?php $data['route'] = 'imports.mapfields';?>
@endif
<div class="container">
<h2>@if(isset($title)) {{$title}} @endif</h2>
<form method="post" 
	action ="{{route($data['route'])}}" 
	name = "mapfields">
{{csrf_field()}}
<table class="table">
<thead>
<th>Input Field</th>
<th>Select Mapping</th>
<th colspan='3'>Example Data</th>
</thead>
<tbody>

        @foreach ($fields[0] as $key=>$field) 
            @if(! in_array($field,$skip))
        <tr>
            <td>
            {{$field}}
            </td>
            <td>
            
            <select name="fields[{{$field}}]">
                <option value="@ignore">ignore</option>
               @foreach ($columns as $column) 
                   @if(! in_array($column->Field,$skip))

                    <option value = '{{$column->Field}}'
                                        
                    @if($field == $column->Field or strtolower(str_replace(" ","_",$field)) == $column->Field) 
                        selected 
                    @endif 
                    >
                    {{$column->Field}}
                    </option>
                    @endif
            @endforeach
            </select>
            </td>
            <td>
            {{$fields[1][$key]}}
            </td>
            <td>
            {{$fields[5][$key]}}
            </td>
            <td>
            {{$fields[9][$key]}}
            </td>

        </tr>
        @endif
        @endforeach 

</tbody>
</table>
<!-- / File location -->
<input type="submit" class="btn btn-success" value="Map Fields" />
<input type="hidden" name="filename" value="{{$data['filename']}}" />
<input type="hidden" name="table" value="{{$data['table']}}" />
@foreach ($data['additionaldata'] as $key=>$value)
<input type="hidden" name="additionaldata[{{$key}}]" value="{{$value}}" />
@endforeach
<input type="hidden" name="type" value="{{$data['type']}}" />
</form>
</div>


@stop
