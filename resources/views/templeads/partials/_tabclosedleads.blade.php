<table class="table" id = "sorttable">
            <thead>

                <th>Company</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Notes</th>
                <th>Ranking</th>

            </thead>
            <tbody>
                @foreach ($closedleads as $lead)
                   
                <tr> 
                    <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->Company_Name}}</a></td>
                    <td>{{$lead->Primary_Address}}</td>
                    <td>{{$lead->Primary_City}}</td>
                    <td>{{$lead->Primary_State}}</td>
                    <td>
                        @foreach ($lead->relatedNotes as $note)
                            {{$note->note}}<hr />
                        @endforeach
                    </td>
                    <td>
                        {{$lead->salesrep->first()->pivot->rating}}

                    </td>
                </tr>  

                @endforeach
            </tbody>



        </table>