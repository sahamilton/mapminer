@extends ('site.layouts.default')
@section('content')
<h1>{{$document->title}}</h1>
<p><strong>Editor:</strong> {{$document->author->person->fullName()}}</p>
<p><strong>Published:</strong> {{$document->created_at->format('Y-m-d')}}</p>
<p><strong>Description:</strong> {{$document->description}}</p>
<p><strong>Summary:</strong> {{$document->summary}}</p>
<p><strong>Type:</strong></p>
<p><strong>Link:</strong> <a href="{{$document->link}}" target="_blank" >{{$document->link}}</a></p>

@if(count($document->rank) > 0 && count($document->score)> 0 && count($document->rankings) >0)
   <?php $rank = round($document->rank[0]->rank,2);?>

@endif
 <div id="{{$document->id}}" data-rating="{{intval($rank)}}" class="starrr" ></div>
            <span id="count-existing">{{$rank}}</span>
@endsection