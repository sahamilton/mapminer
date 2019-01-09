@foreach ($data['leads'] as $lead)

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      
      <th>Company</th>
      <th>Address</th>
      <th>Lead Source</th>
    </thead>
      <tbody>
        @foreach ($lead->leads as $lead)
 
        <tr>         
          <td>
            <a href="{{route('address.show',$lead->id)}}">
              {{$lead->businessname}}
            </a>
          </td>
          <td>{{$lead->fullAddress()}}</td>
          <td>{{$lead->leadsource->source}}</td>
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
@endforeach