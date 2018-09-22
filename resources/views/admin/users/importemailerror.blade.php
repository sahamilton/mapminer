@extends('admin.layouts.default')

@section('content')
	<div class="page-header">
		<h3>Errors in Import:Emails not Unique</h3>
		
			
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<tr>
                @foreach (get_object_vars($importerrors[0]) as $key=>$itemerror)
                <?php $vars[] = $key;?>
                    <th>{{$key}}</th>
                @endforeach 
			</tr>
		</thead>
        <tbody>
           
        @foreach ($importerrors as $importerror)
       
            <tr>
                @foreach($vars as $var)
         
                    <td>{{$importerror->$var}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
