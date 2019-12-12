<form name="account_selector"
method="post"
action="{{route('namdashboard.select')}}" >
@csrf
    <div class="form-group">
  <div class="input-group">
    <select name='account[]'
        multiple=""multiple>

        @foreach ($manager->managesAccount as $account)
            <option value="{{$account->id}}">{{$account->companyname}}</option>
        @endforeach
    </select>
    
   
    <span class="input-group-btn">
        <input id="searchbtn" type="submit" class="form-control btn btn-success" />
    </span>
  </div>
</div>
</form>