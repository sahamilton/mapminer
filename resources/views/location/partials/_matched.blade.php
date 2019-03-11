 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Business Name</th>
    <th>Street</th>
    <th>City</th>
    <th>State</th>
    <th>ZIP</th>

    </thead>
    <tbody>

 @foreach($data['matched'] as $address)

    <tr>

        <td>{{$address->businessname}}</td>
        <td>{{$address->street}}</td>
        <td>{{$address->city}}</td>
        <td>{{$address->state}}</td>
        <td>{{$address->zip}}</td>

    </td>



    </tr>
   @endforeach

    </tbody>
    </table>
   
