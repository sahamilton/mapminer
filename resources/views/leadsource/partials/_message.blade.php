You have new Leads offered to you in the {{$source->title}} campaign. 

{{$source->description}}

These leads are available from  {{$source->datefrom->format('M j, Y')}}  until   {{$source->dateto->format('M j, Y')}}.

These leads are for the following sales verticals:
<ul>
 @foreach ($verticals as $key=>$filter)
<li>{{$filter}}</li>
@endforeach
</ul>

Check out <strong><a href="{{route('salesleads.index')}}">MapMiner</a></strong> to accept these leads and for other resources to help you with these leads.