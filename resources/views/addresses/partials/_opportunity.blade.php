@if($location->opportunities->branch->count()>0)
Tracked as <a href="{{route('opportunity.index')}}">{{$location->opportunities->branch()->first()->branchname}} branch opportunity</a>
@else
  @can('manage_opportunities')
  <form name="addOpportunity" method="post" action="{{route('opportunity.store')}}" >
    @csrf
    @if(count($mybranches)==1)
      <input type="submit" class="btn btn-success" value="add to {{array_values($mybranches)[0]}} branch opportunity" />
      <input type="hidden" name="branch_id" value="{{array_keys($mybranches)[0]}}" >
    @else
      <select name="branch_id" required >

        @foreach($mybranches as $branch_id=>$branch)
          <option value="{{$branch_id}}">{{$branch}}</option>

        @endforeach
      </select>
      <input type="submit" class="btn btn-success" value="add to branch opportunity" />
    @endif
    <input type="hidden" value="{{$location->id}}" name="address_id" />
    
  </form>
  @endcan
@endif