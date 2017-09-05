@extends('admin.layouts.default')
@section('content')

<div class="container">

<form method="post" 
	action ="{{route('imports.mapfields')}}" 
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

        <tr>
            <td>
            {{$field}}
            </td>
            <td>
            
            <select name="field[{{$field}}]">
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
        @endforeach 
</tbody>
</table>
<!-- / File location -->
<input type="submit" class="btn btn-success" value="Map Fields" />
<input type="hidden" name="filename" value="{{$data['file']}}" />
<input type="hidden" name="table" value="{{$data['table']}}" />
@if(isset($source))
<input type="hidden" name="projectsource" value="{{$source}}" />
@endif
@if(isset($company_id))
<input type="hidden" name="company_id" value="{{$company_id}}" />
@endif
</form>
</div>


@stop
