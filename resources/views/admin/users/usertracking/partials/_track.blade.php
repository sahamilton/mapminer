<table class="datatable">
    <thead>
        <th>Date /Time</th>
    </thead>
    <tbody>
        @foreach($data['Track'] as $track)
        <tr>
            <td>{{$track->created_at->format('Y-m-d H:i')}}</td>           
        </tr>
        @endforeach
    </tbody>
</table>