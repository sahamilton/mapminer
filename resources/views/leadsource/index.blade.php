@extends('admin/layouts/default')
@section('content')

<h1>Lead Sources</h1>

@if (auth()->user()->hasRole('admin'))

<div class="float-right">
				<a href="{{{ route('leadsource.create') }}}" class="btn btn-small btn-info iframe">
<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Lead Source</a>
			</div>
@endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Lead Source</th>
    <th>Created</th>
    <th>Description</th>
    <th>Reference</th>
    <th>Leads</th>
    <th>Assigned</th>
    <th>UnAssigned</th>
    <th>Closed Leads</th>
    <th>Average Ranking</th>
    <th>Available From / To</th>

    @if (auth()->user()->hasRole('admin'))

    <th>Actions</th>
    @endif
   
       
    </thead>
    <tbody>
   @foreach($leadsources as $source)
 
    <tr> 
   	<td><a href="{{route('leadsource.show',$source->id)}}">{{$source->source}}</a></td>
    <td>{{$source->created_at->format('Y-m-d')}}</td>
    <td>{{$source->description}}</td>
    <td>{{$source->reference}}</td>
    <td>{{$source->addresses_count}}</td>
    <td>{{$source->assigned}}</td>
    <td><a href="{{route('leadsource.unassigned',$source->id)}}">
       {{$source->unassigned}}</a></td>
    <td>{{$source->closed}}</td>
    
    <td>{{number_format($source->ranking,2)}}</td>
   	<td>
        @if($source->dateto < Carbon\Carbon::now())
            Expired {{$source->datefrom->format('M j,Y')}}
        @elseif ($source->datefrom > Carbon\Carbon::now())
            Commences {{$source->datefrom->format('M j,Y')}}
        @else
            {{$source->datefrom->format('M j,Y')}} - {{$source->dateto->format('M j,Y')}}
        @endif
    </td>


	@if (auth()->user()->hasRole('admin'))

    <td>
    
    
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                Actions <span class="caret"> </span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                

                <a class="dropdown-item"
                href="{{route('leadsource.edit',$source->id)}}">
                <i class="far fa-edit text-info"" aria-hidden="true"></i>
                 Edit this Lead source
                 </a>
                 
                <a class="dropdown-item"
                href="{{route('leadsource.addleads',$source->id)}}">
                <i class="fas fa-plus text-success" aria-hidden="true"></i>
                 Add Leads to this source
                 </a>
                 
                <a class="dropdown-item"
                href="{{route('leadsource.flushleads',$source->id)}}">
                <i class="fas fa-minus-circle text-danger" aria-hidden="true"></i>
                 Flush all Leads from this source
                 </a>
                 
                <a class="dropdown-item"
                href="{{route('leadsource.announce',$source->id)}}">
                <i class="far fa-envelope" aria-hidden="true"></i> 
                Email sales team
                </a>
                
                <a class="dropdown-item"
                data-href="{{route('leadsource.destroy',$source->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead source and all its leads" href="#">
                <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i>
                 Delete this Lead source</a>

            </ul>
        </div>

		
    </td>
   @endif
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_modal')
@include('partials/_scripts')
@endsection
