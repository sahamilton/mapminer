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
            <td>{{ $item->providedBy->person->fullName() }}</td>
            <td>{{ $item->status }}</td>
            <td>
                @foreach ($item->comments as $comment)
                <li>{{ $comment->comment}} {{$comment->by->person->fullName()}}</li>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>