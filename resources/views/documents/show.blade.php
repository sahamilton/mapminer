@extends ('site.layouts.default')
@section('content')

<h1>{{$document->title}}</h1>

<p><strong>Editor:</strong> {{$document->author->person->fullName()}}</p>
<p><strong>Published:</strong> {{$document->created_at->format('M j, Y')}}</p>
<p><strong>Available From:</strong> {{$document->datefrom->format('M j, Y')}}</p>
<p><strong>Available Until:</strong> {{$document->dateto->format('M j, Y')}}</p>
<p><strong>Description:</strong> {{$document->description}}</p>
<p><strong>Summary:</strong> {{$document->summary}}</p>
<p><strong>Type:</strong>{{$document->doctype}}</strong></p>
<p><strong>Location:</strong> <a href="{{$document->location}}" target="_blank" >{{$document->location}}</a></p>

@if(isset($document->rank) && count($document->rank) > 0 && count($document->score) > 0 && count($document->rankings) >0 )
   <?php $rank = round($document->rank[0]->rank,2)?>


 <div id="{{$document->id}}" data-rating="{{intval(isset($rank) ? $rank : 0)}}" class="starrr" >
            Rated: </div>
            Your ranking: {{$document->myranking()->pivot->rank}}

@endif
@include('partials._scripts')
@endsection