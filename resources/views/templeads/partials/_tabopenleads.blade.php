@if(count($openleads)==200)
@php $data['title'] = $person->fullName() @endphp
@include('templeads.partials._limited')

@endif
<table class="table" id = "sorttable">
            <thead>

                <th>Company</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Lead source</th>

            </thead>
            <tbody>
                @foreach ($openleads as $lead)
                   
                <tr> 
                    <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->businessname}}</a></td>
                    <td>{{$lead->address->address}}</td>
                    <td>{{$lead->address->city}}</td>
                    <td>{{$lead->address->state}}</td>
                    <td>{{$lead->leadsource->source}}</td>
                </tr>  

                @endforeach
            </tbody>



        </table>