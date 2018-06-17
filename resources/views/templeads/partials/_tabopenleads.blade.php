@if(count($openleads)==200)
@php $data['title'] = $person->postName() @endphp
@include('templeads.partials._limited')

@endif
<table class="table" id = "sorttable">
            <thead>

                <th>Company</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>

            </thead>
            <tbody>
                @foreach ($openleads as $lead)
                   
                <tr> 
                    <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->Company_Name}}</a></td>
                    <td>{{$lead->Primary_Address}}</td>
                    <td>{{$lead->Primary_City}}</td>
                    <td>{{$lead->Primary_State}}</td>
                </tr>  

                @endforeach
            </tbody>



        </table>