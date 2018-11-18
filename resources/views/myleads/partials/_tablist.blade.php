 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Source</th>
    <th>Rating</th>


    </thead>
    <tbody>
@if (isset($data['result']))
@php $leads = $data['result'] @endphp
@endif
 @foreach($leads as $lead)

    <tr>
        <td>
            <a href="{{route('myleads.show',$lead->id)}}">
                {{ $lead->companyname!='' ? $lead->companyname: $lead->businessname}} 
            </a>
        </td>
        <td>{{$lead->businessname}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>{{$lead->created_at->format('M j, Y')}}</td>
       
        <td>{{$lead->leadsource->source}}</td>
        
        <td></td>



    </tr>
   @endforeach

    </tbody>
    </table>
