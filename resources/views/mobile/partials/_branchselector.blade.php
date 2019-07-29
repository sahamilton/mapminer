
<div class="form-group mx-sm-3 mb-2 inline align-middle">
    <label >Branch</label>
    <select  
    class="form-control"  
    id="branchselect" 
    name="branch" 
    onchange="this.form.submit()">
        <option>Select</option>
        @foreach ($branches as $selbranch)
            <option 
            @if($branch->id == $selbranch->id) selected @endif


            value="{{$selbranch->id}}">{{$selbranch->branchname}}</option>
        @endforeach 
    </select>
</div>
    