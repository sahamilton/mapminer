
<div style="border:1px solid #000;width:500px;margin:20px;padding:20px;float:left">
  <h4>Duplicate Addresses</h4>
  <table id ='sorttable3' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Company</th>
      <th>Full Address</th>
      <th>Count</th>
    </thead>
    <tbody>
      @foreach($data['duplicates'] as $duplicates)
      
        <tr>
        <td>
          @if($duplicates->company)
          <a href ="{{route('company.state',array($duplicates->company_id,$duplicates->state))}}" >
            {{$duplicates->company->companyname}}
          </a>
          @endif
        </td>
        <td><a href="{{route('address.show',$duplicates->address_id)}}">{{$duplicates->fulladdress}}</a></td>
        <td>{{$duplicates->total}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>