@extends('admin.layouts.default')
@section('content')
@if(! isset($data['route']))
@php  $data['route'] = 'imports.mapfields';@endphp
@endif
<div class="container" style="margin-bottom:80px">
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

           
        <tr>
            <td>
            {{$field}}
            </td>
            <td>
            
            <select name="fields[{{$field}}]">
                <option value="@ignore">ignore</option>
                <option value="@add">include (new)</option>
               @foreach ($columns as $column)   

                    <option @if(in_array($column->Field,$requiredFields)) style="color:red" @endif value = '{{$column->Field}}'
                                        
                    @if($field == $column->Field or strtolower(str_replace(" ","_",$field)) == $column->Field) 
                        selected 
                    @endif 
                    >
                    {{$column->Field}}@if(in_array($column->Field,$requiredFields)) *@endif
                    </option>
                  
            @endforeach
            </select>
            </td>
            <td>Default<input type="checkbox" name="default[{{$field}}]"></td>
            <td>
            @if(isset($fields[1])) <input type="text" readonly name="{{$field}}" value="{{$fields[1][$key]}}" /> @endif
            </td>
            <td>
            @if(isset($fields[5]))  {{$fields[5][$key]}} @endif
            </td>
            <td>
             @if(isset($fields[9]))  {{$fields[9][$key]}} @endif
            </td>

        </tr>
     
        @endforeach 

</tbody>
</table>

<!-- / File location -->
<input type="submit" class="btn btn-success" value="Map Fields" />

@if(isset($data['additionaldata']))
    @foreach ($data['additionaldata'] as $key=>$value)
    <input type="hidden" name="additionaldata[{{$key}}]" value="{{$value}}" />
    @endforeach
@endif
@php 
    $hidden = [
        'lead_source_id',
        'step', 
        'contacts', 
        'branch_ids', 
        'file',
        'filename',
        'originalFilename' ,
        'type', 
        'table', 
        'newleadsource', 
        'newleadsourcename', 
        'description',
        'skip',
    ]; 
@endphp
@foreach ($hidden as $hide)
    @if(isset($data[$hide]))
        <input type="hidden" name="{{$hide}}" value="{{$data[$hide]}}" />
    @endif

@endforeach
<input type="hidden" name="user_id" value="{{ auth()->user()->id}}" />
</form>
</div>
@endsection
