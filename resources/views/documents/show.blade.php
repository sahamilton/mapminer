@extends ('site.layouts.default')
@section('content')

<h1>{{$document->title}}</h1>

<p><strong>Editor:</strong> {{$document->author->person->fullName()}}</p>
<p><strong>Published:</strong> {{$document->created_at}}</p>
<p><strong>Available From:</strong> {{$document->datefrom}}</p>
<p><strong>Available Until:</strong> {{$document->dateto}}</p>
<p><strong>Description:</strong> {{$document->description}}</p>
<p><strong>Summary:</strong> {{$document->summary}}</p>
<p><strong>Type:</strong>{{$document->doctype}}</strong></p>
<p><strong>Location:</strong> <a href="{{$document->location}}" target="_blank" >{{$document->location}}</a></p>

@if(isset($document->rank) && count($document->rank) > 0 && count($document->score) > 0 && count($document->rankings) >0 )
   <?php $rank = round($document->rank[0]->rank,2)?>


 <div id="{{$document->id}}" data-rating="{{intval(isset($rank))}}" class="starrr" ></div>
            <span id="count-existing">{{$rank}}</span>
@endif
@endsection