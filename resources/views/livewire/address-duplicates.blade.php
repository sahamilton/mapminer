<div>
    <table class ='table table-bordered table-striped table-hover'>
        <thead>
            <th>Company Name</th>
            <th>Type</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            <th>Assigned To Branch</th>
            <th>Merge</th>
        </thead>
        <tbody>
            @foreach ($address->duplicates as $account)
                @if($account->id != $address->id)
                <tr>
                    
                    <td><a href="{{route('address.show', $account->id)}}">{{$account->businessname}}</a></td>
                    <td>{{$account->addressable_type}}</td>
                    <td>{{$account->street}}</td>
                    <td>{{$account->city}}</td>
                    <td>{{$account->state}}</td>
                    <td>{{$account->zip}}</td>
                    <td>
                        @foreach ($account->assignedToBranch as $branch)
                            <li>{{$branch->branchname}}</li>
                        @endforeach
                    </td>
                    </td>
                    <td>
                        
                        @if(array_intersect($account->assignedToBranch->pluck('id')->toArray(), $myBranches) or  $account->assignedToBranch->count()==0)
                        Merge into {{$address->businessname}}
                        <input type="checkbox" checked name="address[]" value="{{$account->id}}"/>
                        @else
                        <p class="text-danger">Not Owned by any of your branches</p>
                        @endif
                    </td>
                    

                </tr>
                @endif
            @endforeach
        </tbody>

    </table>
    <input type="hidden" name="original" value="{{$address->id}}" />
</div>
