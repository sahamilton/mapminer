<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Feedback</th>
        <th>Posted By</th>
        <th>Status</th>
        <th>Comments</th>
       
    </tr>
    </thead>
    <tbody>
    @foreach($feedback as $item)

        <tr>
            <td>{{ $item->created_at->format('Y-m-d') }}</td>
            <td>{{ $item->category->category }}</td>
            <td>{{ $item->feedback }}</td>
            <td>
                @if($item->providedBy)
                    {{ $item->providedBy->person->fullName() }}
                @else
                    No Longer a Mapminer User
                @endif
            </td>
            <td>{{ $item->status }}</td>
            <td>
                @foreach ($item->comments as $comment)
                    <li>{{ $comment->comment}} 
                        @if($comment->by)
                            {{$comment->by->person->fullName()}}
                        @else
                            No longer a mpminer user
                        @endif
                    </li>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>