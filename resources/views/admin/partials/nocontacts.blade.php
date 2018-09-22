<div style="border:1px solid #000;width:500px;margin:20px;padding:20px;float:left">
  <h4>Companies with Locations without Contacts</h4>
  <table id ='sorttable4' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Company</th>
      <th>Locations</th>
      <th>Locations w/o Contact</th>
      <th>Percent of Total</th>
    </thead>
    <tbody>
      @foreach($data['nocontact'] as $nocontact)

        <tr>
          <td>
            <a href="{{route('company.show',$nocontact->id)}}" >
              {{$nocontact->companyname}}
            </a>
          </td>
          <td>{{$nocontact->locations}}</td>
          <td>{{$nocontact->without}}</td>
          <td>{{number_format($nocontact->percent).'%'}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>