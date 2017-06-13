

    <div class="row">
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Company Name</th>

                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
                <th>Industry Vertical</th>
                <th>Rating</th>
                <th></th>

            </thead>
            <tbody>

                @foreach ($leads->ownedLeads as $lead)
                    <?php $rank = $lead->pivot->rating;?>
                <tr>
                    <td>{{$lead->companyname }}</td>
                    <td>
                    @if(isset($lead->pivot) && $lead->ownsLead($lead->id))
                    @if($manager)
                        <a href="{{route('salesleads.showrepdetail',[$lead->id,$leads->id])}}" />
                    @else
                        <a href="{{route('salesleads.show',$lead->id)}}" />
                    @endif
                        {{$lead->businessname}}</a></td>
                    @else
                        {{$lead->businessname}} 
                    @endif
                    </td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
       
                    <td>
                        <ul>
                        @foreach ($lead->vertical as $vertical)
                            <li>{{$vertical->filter}}</li>
                        @endforeach
                        </ul>
                    </td>
                    <td>

                    <div id="{{$lead->id}}" data-rating="{{intval(isset($rank) ? $rank : 0)}}" class="starrr lead" >
                    
           </div>
                    </td>
                    <td>
                    @include ('salesleads.partials._closeleadform')
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Close Lead</button>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

