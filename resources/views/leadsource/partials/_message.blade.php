You have new propsects offered to you in the {{$source->title}} campaign. 

{{$source->description}}

These propsects are available from  {{$source->datefrom->format('M j, Y')}}  until   {{$source->dateto->format('M j, Y')}}.

These prospects are for the following sales verticals:
<ul>
 @foreach ($verticals as $key=>$filter)
<li>{{$filter}}</li>
@endforeach
</ul>

Check out <strong><a href="{{route('salesleads.index')}}">MapMiner</a></strong> to accept these proposects and for other resources to help you with these prospects.