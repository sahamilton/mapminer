@extends('admin/layouts/default')
@section('content')

<h1>Prospect Sources</h1>


@if (Auth::user()->hasRole('Admin'))

<div class="pull-right">
				<a href="{{{ route('leadsource.create') }}}" class="btn btn-small btn-info iframe">
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
 Create New Prospect Source</a>
			</div>
@endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Prospect Source</th>
    <th>Description</th>
    <th>Reference</th>
    <th>Prospects</th>
    <th>Assigned to</th>
    <th>Average Ranking</th>
    <th>Available From / To</th>

    @if (Auth::user()->hasRole('Admin'))
    <th>Actions</th>
    @endif
   
       
    </thead>
    <tbody>
   @foreach($leadsources as $source)
    <tr> 
   	<td><a href="{{route('leadsource.show',$source->id)}}">{{$source->source}}</a></td>
    <td>{{$source->description}}</td>
    <td>{{$source->reference}}</td>
    <td>{{count($source->leads)}}</td>
    <td>{{$source->assignedTo()}}</td>
    <td>
        @if(count($source->leads)>0)
            {{number_format($source->leads[0]->leadRank(),2)}}
        @endif
    </td>
   	<td>
        @if($source->dateto < Carbon\Carbon::now())
            Expired {{$source->datefrom->format('M j,Y')}}
        @elseif ($source->datefrom > Carbon\Carbon::now())
            Commences {{$source->datefrom->format('M j,Y')}}
        @else
            {{$source->datefrom->format('M j,Y')}} - {{$source->dateto->format('M j,Y')}}
        @endif
    </td>

	@if (Auth::user()->hasRole('Admin'))
    <td>
     @include('partials/_modal')
    
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                Actions <span class="caret"> </span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                
                <li>
                <a href="{{route('leadsource.edit',$source->id)}}">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                 Edit this prospect source
                 </a>
                 </li>

                <li>

                <li>
                <a href="{{route('leadsource.addleads',$source->id)}}">
                <i class="fa fa-plus text-success" aria-hidden="true"></i>
                 Add prospects to this source
                 </a>
                 </li>
                <li>
                <a href="{{route('leadsource.flushleads',$source->id)}}">
                <i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>
                 Flush all prospects from this source
                 </a>
                 </li>
                <li>
                <a href="{{route('leadsource.announce',$source->id)}}">
                <i class="fa fa-envelope" aria-hidden="true"></i> 
                Email sales team
                </a>
                </li>
                <li>
                <a data-href="{{route('leadsource.destroy',$source->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead source and all its leads" href="#">
                <i class="fa fa-trash-o" aria-hidden="true"> </i>
                 Delete this prospect source</a>
                 </li>
            </ul>
        </div>

		
    </td>
   @endif
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')
@stop