@extends('site.layouts.default')
@section('content')

<h1>MyBranches Leads</h1>


 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Business Name</th>
    <th>Address</th>
    
    <th>Branches</th>
    <th>Reassign</th>

    </thead>
    <tbody>

        @foreach($leads as $lead)

    <tr>
        <td>
            <a href="{{route('myleads.show',$lead->id)}}">
                {{ $lead->companyname != '' ? $lead->companyname: $lead->businessname}} 
            </a>
        </td>
        <td>{{$lead->businessname}}</td>
        <td>{{$lead->fullAddress()}}</td>
        <td>
                @if($lead->assignedToBranch->count()>0)
                @php $branchesassigned = $lead->assignedToBranch->pluck('id')->toArray(); @endphp
              @else
                       @php  $branchesassigned = array(); @endphp
                @endif
        @foreach ($myBranches as $id=>$branchname)
                <li><input type="checkbox"
                @if(in_array($id,$branchesassigned))
                checked
                @endif
                />{{$branchname}} distance </li>

        @endforeach
            
        </td>
           

        <td>
                Reassign
        </td>



    </tr>
   @endforeach

    </tbody>
    </table>
   


  
@include('partials._scripts')
@endsection
