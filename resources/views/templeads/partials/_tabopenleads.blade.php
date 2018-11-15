@if($openleads->count()==200)
@php $data['title'] = $person->postName() @endphp
@include('templeads.partials._limited')

@endif
<table class="table" id = "sorttable">
            <thead>

                <th>Company</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>

                <th>Distance (miles)</th>

                <th>Lead source</th>

            </thead>
            <tbody>
                @foreach ($openleads as $lead)
                   
                <tr> 
                    <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->businessname}}</a></td>
                    <td>{{$lead->address}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>

                    <td>{{number_format($lead->distance,1)}}</td>

                    <td>{{$lead->leadsource->source}}</td>
                </tr>  

                @endforeach
            </tbody>



        </table>