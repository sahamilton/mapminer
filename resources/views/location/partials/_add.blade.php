 <table id ='nosorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Business Name</th>
    <th>Street</th>
    <th>City</th>
    <th>State</th>
    <th>ZIP</th>
    <th>Lat</th>
    <th>Lng</th>
    <th>Add</th>
    </thead>
    <tbody>

 @foreach($data['add'] as $address)

    <tr>

        <td>{{$address->businessname}}</td>
        <td>{{$address->street}}</td>
        <td>{{$address->city}}</td>
        <td>{{$address->state}}</td>
        <td>{{$address->zip}}</td>
        <td>{{$address->lat}}</td>
        <td>{{$address->lng}}</td>
        <td><input type="checkbox" checked name="add[]" value="{{$address->id}}"></td>

    </td>



    </tr>
   @endforeach

    </tbody>
    </table>
   
