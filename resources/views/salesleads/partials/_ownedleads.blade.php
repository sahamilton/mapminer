

    <div class="row">
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Company Name</th>

                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
                <th>Status</th>
                <th>Industry Vertical</th>

            </thead>
            <tbody>

                @foreach ($leads->ownedLeads as $lead)

                <tr>
                    <td>{{$lead->companyname }}</td>
                    <td>
                    @if(isset($lead->pivot) && $lead->ownsLead($lead->id))
                    @if($manager)!!
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
                    <td>{{$statuses[$lead->pivot->status_id]}}</td>
                    <td>
                        <ul>
                        @foreach ($lead->vertical as $vertical)
                            <li>{{$vertical->filter}}</li>
                        @endforeach
                        </ul>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

