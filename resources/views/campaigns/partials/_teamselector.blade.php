<div class="col-sm-6">
    <form
        name="teamselector"
        id="teamselector"
        method="post"
        action="{{route('campaigns.report', $campaign->id)}}"
        >
    @csrf
    @include('campaigns.partials._mgrselector')
        <div class="form-group">
            <input type="submit" 
                class="btn btn-info"
                value="Select"
                />
        </div>
    </form>
</div>
