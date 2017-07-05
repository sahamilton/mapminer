<td style ="text-align: center; vertical-align: middle;">
<input {{in_array($location->id,$mywatchlist) ? 'checked' : ''}}
id="{{$location->id}}" 
type='checkbox' name='watchList' class='watchItem' 
value="{{$location->id}}" >
</td>