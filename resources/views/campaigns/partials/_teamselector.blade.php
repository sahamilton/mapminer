<div class="col-sm-6">
    
    <form
        name="teamselector"
        id="teamselector"
        method="post"
        action="{{route($route, $campaign->id)}}"
        >
    @csrf
    @include('campaigns.partials._mgrselector')
   
    </form>
</div>