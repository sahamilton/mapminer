@extends ('admin.layouts.default')
@section('content')
<h2>Lead Source</h2>
<h4>{{$leadsource->source}}</h4>

<p><strong>Editor:</strong> {{$leadsource->author->person->postName()}}</p>
<p><strong>Created:</strong> {{$leadsource->created_at->format('M j, Y')}}</p>
<p><strong>Available From:</strong> {{$leadsource->datefrom->format('M j, Y')}}</p>
<p><strong>Available Until:</strong> {{$leadsource->dateto->format('M j, Y')}}</p>
<p><strong>Description:</strong> {{$leadsource->description}}</p>
<p><strong>Number of Leads:</strong>{{count($leadsource->leads)}}</p>
@include('leadsource.partials.leads')
@include('partials._scripts')
@endsection