@php $total = 0; @endphp
<h2>Related Notes</h2>


<table id ='sorttable9' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Date</th>
    <th>Note</th>
    <th>Author</th>

    </thead>
    <tbody>
        
     @foreach ($data['notes'] as $note)


        <tr>
           <td><a href="{{route('address.show',$note->relatesToLocation->id)}}">{{$note->relatesToLocation->businessname}}</a></td>
            <td>{{$note->created_at->format('Y-m-d')}}</td>
            <td>{!! $note->note !!}</td>

            <td>@if ( $note->writtenby) {{$note->writtenby->person->fullName()}} @else No Longer with the Company @endif</td>
        </tr>
        @endforeach
        
    </tbody>
</table>
<p>