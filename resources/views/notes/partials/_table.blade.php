 @if(isset($location))<div class="float-right">
    <a class="btn btn-info" 
        title="Add Note"
        data-href="{{route('notes.store')}}" 
        data-toggle="modal" 
        data-target="#add-note" 
        data-title = "Add note to address" 
        href="#">
        <i class="fas fa-pencil-alt"></i>
        Add Note
        </a>
    </div>
    @endif
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>Company</th>
    <th>Location Name</th>
    <th>Address</th>
    <th>Note</th>
    <th>Posted By</th>
    <th>Date</th>
   
       
    </thead>
    <tbody>
@if(isset($notes))
   @foreach($notes as $note)

        @if($note->relatesToLocation)
        <tr>  
    	<td>
         @if(isset($note->relatesToLocation->company))
            <a href ="{{route('notes.company',$note->relatesToLocation->company->id)}}"
            title = "See all {{$note->relatesToLocation->company->companyname}} location notes">
            {{$note->relatesToLocation->company->companyname}}
            </a>
         @endif
         @if($note->user_id && $note->writtenBy && (auth()->user()->hasRole(['admin','sales_operations']) or auth()->user()->id == $note->writtenBy->id))
         <a href="{{route('notes.edit',$note->id)}}" title="Edit this note"><i class="fas fa-edit"></i></a>
         <a             data-href="{{route('notes.destroy',$note->id)}}" 
                        data-toggle="modal" 
                        data-target="#confirm-delete" 
                        data-title = "this note" 
                        href="#"
                        title="Delete this note">
         <i class="fas fa-trash-alt text-danger"></i></a>
         
         @endif
        </td>
        <td>
            <a href="{{route('address.show',$note->relatesToLocation->id)}}"
            title ="Review all notes at this  location" >
                {{$note->relatesToLocation->businessname}}
            </a>
        </td>
        <td>
        {{ucwords(strtolower($note->relatesToLocation->city))}}, {{strtoupper($note->relatesToLocation->state)}}
        </td>
        <td>{{$note->note}}</td>
        <td>
        @if(isset($note->writtenBy) && null!== $note->writtenBy->person)
            {{$note->writtenBy->person->fullName()}}
        @else
            No Longer with Company
        @endif
        </td>
        <td>{{$note->created_at->format('M j, Y')}}</td>
        </tr>
        @endif
   @endforeach
   @endif 
    </tbody>
    </table>
    @if(isset($location))
    @include('notes.partials._note')
    @endif