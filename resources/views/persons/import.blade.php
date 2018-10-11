@extends('admin/layouts/default')
@section('content')
@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
<h2>Steps to import managers</h2>
<ol>
<li>First create your csv file of people (not users) from the template.  Do not change, add or delete any field / column</li>
<li>Save the CSV file locally on your computer.</li>
<li>Select the file and import</li>
</ol>

{{ Form::open(array('route'=>'person.import', 'files' => true)) }}


<div>


<div>
{{Form::file('upload')}}
{{ $errors->first('upload') }}
</div></div>
<div>

{{Form::submit('Import Managers',['class' => 'btn btn-xs btn-success'])}}
</div>
{{Form::close()}}
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
