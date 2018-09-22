

    <div class="row">
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Company Name</th>

                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
                <th>Industry Vertical</th>
                <th>Rating</th>
                
                <th>Status</th>

            </thead>
            <tbody>

                @foreach ($leads->ownedLeads as $lead)

                    <?php $rank = $lead->pivot->rating;?>
               
                <tr>
                    <td>{{$lead->companyname }}</td>
                    <td>
                   
                   
                        <a href="{{route('salesleads.show',$lead->id)}}" />
                  
                        {{$lead->businessname}}</a></td>
                   
                   
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
                    <td>@if($lead->pivot->status_id !=3)
                    @include ('leads.partials._closeleadform')
                    <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal">Close Lead</button>
                        @else
                        Lead Closed
                        @endif
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

