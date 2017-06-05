
<div id="home" class="tab-pane fade in active">
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
                    @if(isset($lead->pivot) && $lead->pivot->status_id == 2)
                        <a href="{{route('salesleads.show',$lead->id)}}" />
                        {{$lead->businessname}}</td>
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
</div>
