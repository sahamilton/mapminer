<div class="row">
	<h2> Sales Resources</h2>
	@foreach ($activity->relatedDocuments() as $document)

		<h4><a href="{{route('documents.show',$document->id)}}" title="See {{$document->title}} document details">{{$document->title}}</a></h4>
		<p>{{$document->summary}}</p>
		@if(count($document->rankings) >0)
			<?php $rank = round($document->score[0]->score/count($document->rankings));
			$count = count($document->rankings);
			$avg = round($document->score[0]->score/count($document->rankings),2)?>
		@else
			<?php $rank = null;
			$count=0;
			$avg = 0;?>
		@endif
		<div id="{{$document->id}}" data-rating="{{$rank}}" class="starrr" >
         <span id="count-existing"> {{$count}} ratings averaging {{$avg}} </span></div>
		
		@if($document->myranking())
		Your Ranking: {{$document->myranking()->pivot->rank}}
		@endif
	
		<p><a href="{{$document->location}}" target="_blank">View document</a></p>
		<hr />

	@endforeach
</div>