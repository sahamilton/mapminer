@extends('admin.layouts.default')

@section('content')
	<div class="page-header">
       
		<h3>Errors in Import:{{ucwords($field)}}s already exist.</h3>
		
			
	</div>
<form method="post" name="inputErrors" action="{{route('fixuserinputerrors')}}" >
    {{csrf_field()}}
	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
                <td>Exclude<input type="checkbox" id='selectall'></td>
                @foreach (get_object_vars($importerrors[0]) as $key=>$itemerror)
                <?php $vars[] = $key;?>
                    <th>{{$key}}</th>
                @endforeach 
			</tr>
		</thead>
        <tbody>
           
        @foreach ($importerrors as $importerror)
       
            <tr>
                <td><input class = "watchItem" type="checkbox" name="skip[]" value="{{$importerror->id}}" /></td>
                @foreach($vars as $var)
                    
                    <td>
                        @if($field == $var)
                            <input type="text" name="error[{{$importerror->$var}}]" value="{{$importerror->$var}}" />
                        @else

                        {{$importerror->$var}}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    <input type="hidden" name="field" value="{{$field}}" />
    <input type="submit" name="fixFile" value="Fix File" />
    <input type="submit" name="fixInput" value="Fix Input" />
</form>
@include('partials._scripts')
@endsection
