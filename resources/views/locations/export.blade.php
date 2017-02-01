@extends('site/layouts/default')
@section('content')
<h2>Export locations for a national account</h2>
<ol>
<li>Select the company that locations belong to from the list</li>

</ol>

{{ Form::open(array('route'=>'companies.export', 'files' => true)) }}
<div>
{{Form::label('company','Select Company:')}}
<div>{{Form::select('company',$companies)}}
{{ $errors->first('company') }}
</div></div>




<div>

{{Form::submit('Export',['class' => 'btn btn-sm btn-success'])}}
</div>
{{Form::close()}}
@stop
