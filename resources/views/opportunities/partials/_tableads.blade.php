<div class="row float-right"><button type="button" 
    class="btn btn-info float-right" 
    data-toggle="modal" 
    data-target="#add_lead">
      Add Lead
</button>
</div>
@foreach ($data['leads'] as $lead)

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      
      <th>Company</th>
      <th>Address</th>
      <th>Lead Source</th>
      <th>Remove</th>
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
          <td>
            @if($lead->leadsource)
              {{$lead->leadsource->source}}
            @endif
          </td>
          <td>
            
      <a 
        data-href="{{route('branch.lead.remove',$lead->id)}}" 
        data-toggle="modal" 
        data-target="#confirm-remove" 
        data-title = " this lead from your list" 
        href="#">
            <i class="fas fa-trash-alt text-danger"></i></a></td>
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
@endforeach
@include('partials._branchleadmodal')
@include('opportunities.partials._mylead')