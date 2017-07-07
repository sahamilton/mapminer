<td style ="text-align: center; vertical-align: middle;">
<input @if(in_array($location->id,$mywatchlist)) checked @endif
id="{{$location->id}}" 
type='checkbox' name='watchList' class='watchItem' 
value="{{$location->id}}" >
</td>