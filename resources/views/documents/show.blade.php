@extends ('site.layouts.default')
@section('content')
<h1>{{$document->title}}</h1>
<p><strong>Editor:</strong> {{$document->author->person->fullName()}}</p>
<p><strong>Published:</strong> {{$document->created_at->format('Y-m-d')}}</p>
<p><strong>Description:</strong> {{$document->description}}</p>
<p><strong>Summary:</strong> {{$document->summary}}</p>
<p><strong>Type:</strong></p>
<p><strong>Link:</strong> <a href="{{$document->link}}" target="_blank" >{{$document->link}}</a></p>


@endsection