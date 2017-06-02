@extends('admin.layouts.default')

@section('content')
<div class="container">
<h2>Sales Leads assigned to {{$leads->firstname}} {{$leads->lastname}}</h2>
<div class="container">
       
<div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Company Name</th>
                
                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
                <th>Status</th>
                <th>Industry Vertical</th>
                <th>Actions</th>
                
            </thead>
            <tbody>

            @foreach ($leads->salesleads as $lead)
        
                <tr> 
                
                <td>{{$lead->companyname }}</td>
                <td>{{$lead->businessname}}</td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>{{$lead->pivot->status_id}}
                <td>
                <ul>
                @foreach ($lead->vertical as $vertical)
                    <li>{{$vertical->filter}}</li>
                @endforeach
                </ul>
                </td>

                <td class="col-md-2">
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</td>
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
    </div>
</div>
@include('partials._scripts')
@endsection
